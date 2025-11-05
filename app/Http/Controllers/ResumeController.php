<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;
use App\Models\AwardFile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    // ==================== WELCOME PAGE WITH ALL USERS ==================== //
    public function welcome()
    {
        // Fetch all users who have resumes
        $users = User::has('resume')->get();
        return view('welcome', compact('users'));
    }

    // ==================== SHOW PUBLIC RESUME BY USER ID ==================== //
    public function show($id)
    {
        $resume = Resume::with('awardFiles')->where('user_id', $id)->firstOrFail();
        $resume->awardFiles = $resume->awardFiles ?? collect();

        $fields = ['organization','education','skills','programming','projects','interests'];
        foreach ($fields as $f) {
            if (isset($resume->$f)) {
                $decoded = is_string($resume->$f) ? json_decode($resume->$f, true) : $resume->$f;
                $resume->$f = is_array($decoded) ? $decoded : [];
            } else {
                $resume->$f = [];
            }
        }

        return view('public_resume', compact('resume'));
    }

    // ==================== VIEW LOGGED-IN USER RESUME (dashboard) ==================== //
    public function resume()
    {
        if (!session()->has('user_id')) return redirect('/login');

        $userId = session('user_id');

        // Use firstOrNew to avoid mass-assignment pitfalls and explicitly set defaults and user_id
        $resume = Resume::firstOrNew(['user_id' => $userId]);

        if (! $resume->exists) {
            // Apply defaults and ensure user_id is set before saving
            $defaults = $this->defaultResumeData($userId);

            // Fill only attributes allowed by fillable
            $resume->fill($defaults);
            $resume->user_id = $userId;

            // Save newly created resume
            $resume->save();
        }

        $resume->load('awardFiles');
        $resume->awardFiles = $resume->awardFiles ?? collect();
        $resume->interests = is_array($resume->interests) ? $resume->interests : [];
        $resume->projects = is_array($resume->projects) ? $resume->projects : [];
        $resume->organization = is_array($resume->organization) ? $resume->organization : ['name' => [], 'position' => [], 'year' => []];
        $resume->skills = is_array($resume->skills) ? $resume->skills : ['key' => [], 'value' => []];
        $resume->programming = is_array($resume->programming) ? $resume->programming : ['key' => [], 'value' => []];

        return view('resume', compact('resume'));
    }

    // ==================== CREATE (reuse edit blade) ==================== //
    // Reuse the same edit_resume blade — when creating we pass null so blade shows empty fields.
    public function create()
    {
        if (!session()->has('user_id')) return redirect('/login');

        // Pass null resume (blade handles create mode)
        return view('edit_resume', ['resume' => null]);
    }

    // ==================== SHOW EDIT FORM (also used for create when $id is null) ==================== //
    public function editResume($id = null)
    {
        if (!session()->has('user_id')) return redirect('/login');

        // If no id provided, open the same edit blade for creation
        if (empty($id)) {
            return view('edit_resume', ['resume' => null]);
        }

        $resume = Resume::with('awardFiles')
                        ->where('id', $id)
                        ->where('user_id', session('user_id'))
                        ->firstOrFail();

        $resume->awardFiles = $resume->awardFiles ?? collect();
        $resume->organization = is_array($resume->organization) ? $resume->organization : ['name' => [], 'position' => [], 'year' => []];
        $resume->education = is_array($resume->education) ? $resume->education : [];
        $resume->interests = is_array($resume->interests) ? $resume->interests : [];
        $resume->skills = is_array($resume->skills) ? $resume->skills : ['key' => [], 'value' => []];
        $resume->programming = is_array($resume->programming) ? $resume->programming : ['key' => [], 'value' => []];
        $resume->projects = is_array($resume->projects) ? $resume->projects : [];

        return view('edit_resume', compact('resume'));
    }

    // ==================== STORE RESUME (create new) ==================== //
    public function storeResume(Request $request)
    {
        if (!session()->has('user_id')) return redirect('/login');

        $rules = $this->validationRules();
        $validated = $request->validate($rules);

        $resume = new Resume();
        $resume->user_id = session('user_id');

        // Handle cropped profile image (if present) BEFORE saving so profile_photo is stored on model
        if ($request->filled('cropped_image')) {
            if (preg_match('/^data:(image\/\w+);base64,/', $request->cropped_image, $matches)) {
                $image = preg_replace('/^data:image\/\w+;base64,/', '', $request->cropped_image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'resume_' . session('user_id') . '_' . time() . '.png';
                Storage::disk('public')->put('resume_photos/' . $imageName, base64_decode($image));
                $resume->profile_photo = 'resume_photos/' . $imageName;
            }
        }

        $this->saveResumeData($resume, $request);
        $resume->save();

        // Attach pending spotlight if exists (from Home upload)
        if (session()->has('pending_spotlight')) {
            $pending = session('pending_spotlight'); // e.g. temp_spotlights/spotlight_7_xyz.png
            if ($pending && Storage::disk('public')->exists($pending)) {
                // ensure final dir exists
                $finalDir = 'profile_photos';
                if (! Storage::disk('public')->exists($finalDir)) {
                    Storage::disk('public')->makeDirectory($finalDir);
                }

                // create final path (use same filename)
                $finalName = basename($pending);
                $finalPath = $finalDir . '/' . $finalName;

                // move file
                try {
                    Storage::disk('public')->move($pending, $finalPath);
                } catch (\Exception $e) {
                    // if move fails, attempt to copy then delete
                    try {
                        $binary = Storage::disk('public')->get($pending);
                        Storage::disk('public')->put($finalPath, $binary);
                        Storage::disk('public')->delete($pending);
                    } catch (\Exception $inner) {
                        // failed to attach; clear session to avoid loop and continue
                        session()->forget('pending_spotlight');
                    }
                }

                // delete old spotlight if exists
                if (!empty($resume->spotlight_photo) && Storage::disk('public')->exists($resume->spotlight_photo)) {
                    try { Storage::disk('public')->delete($resume->spotlight_photo); } catch (\Exception $e) {}
                }

                // attach and save (this will update updated_at)
                $resume->spotlight_photo = $finalPath;
                $resume->save();

                // clear session pointer
                session()->forget('pending_spotlight');
            } else {
                // pending file missing — clear session
                session()->forget('pending_spotlight');
            }
        }

        // Save awards after resume exists (award handling uses resume->id)
        $this->saveAwards($resume, $request);

        // Redirect back to the edit page for the newly created resume with success message
        return redirect()->route('resume.edit', ['id' => $resume->id])->with('success', 'Resume created successfully!');
    }

    // ==================== UPDATE RESUME ==================== //
    public function updateResume(Request $request, $id)
    {
        if (!session()->has('user_id')) return redirect('/login');

        $rules = $this->validationRules();
        $validated = $request->validate($rules);

        $resume = Resume::where('id', $id)
                        ->where('user_id', session('user_id'))
                        ->firstOrFail();

        // Handle cropped profile image
        if ($request->filled('cropped_image')) {
            if ($resume->profile_photo && Storage::disk('public')->exists($resume->profile_photo)) {
                try { Storage::disk('public')->delete($resume->profile_photo); } catch (\Exception $e) {}
            }

            if (preg_match('/^data:(image\/\w+);base64,/', $request->cropped_image, $matches)) {
                $image = preg_replace('/^data:image\/\w+;base64,/', '', $request->cropped_image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'resume_' . session('user_id') . '_' . time() . '.png';
                Storage::disk('public')->put('resume_photos/' . $imageName, base64_decode($image));
                $resume->profile_photo = 'resume_photos/' . $imageName;
            }
        }

        $this->saveResumeData($resume, $request);
        $resume->save();

        // Attach pending spotlight if exists (from Home upload)
        if (session()->has('pending_spotlight')) {
            $pending = session('pending_spotlight'); // e.g. temp_spotlights/spotlight_7_xyz.png
            if ($pending && Storage::disk('public')->exists($pending)) {
                // ensure final dir exists
                $finalDir = 'profile_photos';
                if (! Storage::disk('public')->exists($finalDir)) {
                    Storage::disk('public')->makeDirectory($finalDir);
                }

                // create final path (use same filename)
                $finalName = basename($pending);
                $finalPath = $finalDir . '/' . $finalName;

                // move file
                try {
                    Storage::disk('public')->move($pending, $finalPath);
                } catch (\Exception $e) {
                    // fallback copy-then-delete
                    try {
                        $binary = Storage::disk('public')->get($pending);
                        Storage::disk('public')->put($finalPath, $binary);
                        Storage::disk('public')->delete($pending);
                    } catch (\Exception $inner) {
                        session()->forget('pending_spotlight');
                    }
                }

                // delete old spotlight if exists
                if (!empty($resume->spotlight_photo) && Storage::disk('public')->exists($resume->spotlight_photo)) {
                    try { Storage::disk('public')->delete($resume->spotlight_photo); } catch (\Exception $e) {}
                }

                // attach and save (this will update updated_at)
                $resume->spotlight_photo = $finalPath;
                $resume->save();

                // clear session pointer
                session()->forget('pending_spotlight');
            } else {
                // pending file missing — clear session
                session()->forget('pending_spotlight');
            }
        }

        $this->saveAwards($resume, $request);

        // Redirect back to the edit page for this resume with success message
        return redirect()->route('resume.edit', ['id' => $resume->id])->with('success', 'Resume updated successfully!');
    }

    // ==================== DELETE RESUME ==================== //
    public function deleteResume($id)
    {
        if (!session()->has('user_id')) return redirect('/login');

        $resume = Resume::where('id', $id)
                        ->where('user_id', session('user_id'))
                        ->firstOrFail();

        if ($resume->profile_photo && Storage::disk('public')->exists($resume->profile_photo)) {
            try { Storage::disk('public')->delete($resume->profile_photo); } catch (\Exception $e) {}
        }

        if ($resume->spotlight_photo && Storage::disk('public')->exists($resume->spotlight_photo)) {
            try { Storage::disk('public')->delete($resume->spotlight_photo); } catch (\Exception $e) {}
        }

        foreach ($resume->awardFiles as $award) {
            if ($award->file_path && Storage::disk('public')->exists($award->file_path)) {
                try { Storage::disk('public')->delete($award->file_path); } catch (\Exception $e) {}
            }
            $award->delete();
        }

        $resume->delete();

        // Redirect to home/dashboard after deletion
        return redirect()->route('home')->with('success', 'Resume deleted successfully!');
    }

    // ==================== DOWNLOAD RESUME ==================== //
    public function downloadResume($id = null)
    {
        $resume = $id ? Resume::with('awardFiles')->find($id) : Resume::with('awardFiles')->latest()->first();
        if (!$resume) return redirect('/')->with('error', 'Resume not found.');

        $resume->awardFiles = $resume->awardFiles ?? collect();

        return response()->view('resume.resume', compact('resume'))
                        ->header('Content-Type', 'text/html')
                        ->header('Content-Disposition', 'attachment; filename="' . ($resume->fullname ?? 'Resume') . '.html"');
    }

    // ==================== HELPER FUNCTIONS ==================== //
    private function saveResumeData(&$resume, $request)
    {
        $resume->fullname = $request->fullname;
        $resume->dob = $request->dob;
        $resume->pob = $request->pob;

        // Map gender input to civil_status column.
        // If user chose 'prefer_not_to_say' we store null (so public resume won't show the field).
        $gender = $request->input('gender', $request->input('civil_status', null));
        if ($gender === 'prefer_not_to_say' || $gender === '' || $gender === null) {
            $resume->civil_status = null;
        } else {
            // store the explicit selection (male/female) in civil_status column
            $resume->civil_status = $gender;
        }

        $resume->specialization = $request->specialization;
        $resume->email = $request->email;
        $resume->phone = $request->phone;
        $resume->address = $request->address;

        $resume->organization = [
            'name' => $request->input('organization.name', []),
            'position' => $request->input('organization.position', []),
            'year' => $request->input('organization.year', []),
        ];

        // Keep arrays / json consistent
        $resume->interests = $request->interests ?? [];
        $resume->education = $request->education ?? [];
        $resume->skills = $request->skills ?? [];
        $resume->programming = $request->programming ?? [];
        $resume->projects = $request->projects ?? [];
    }

    private function saveAwards($resume, $request)
    {
        // Keep your existing award handling code
        $awardNames     = $request->input('awards.name', []);
        $existingFiles  = $request->input('awards.existing_file', []);
        $uploadedFiles  = $request->file('awards.file') ?? [];

        $processedAwardIds = [];
        $max = max(count($awardNames), count($existingFiles), count($uploadedFiles));

        for ($i = 0; $i < $max; $i++) {
            $name = isset($awardNames[$i]) ? trim($awardNames[$i]) : null;
            $existing = isset($existingFiles[$i]) ? $existingFiles[$i] : null;
            $uploaded = isset($uploadedFiles[$i]) ? $uploadedFiles[$i] : null;

            if (empty($name) && empty($existing) && empty($uploaded)) continue;

            $award = AwardFile::where('resume_id', $resume->id)
                              ->where('award_name', $name)
                              ->first();

            $filePath = null;

            if ($uploaded && $uploaded instanceof \Illuminate\Http\UploadedFile) {
                $fileName = 'award_' . session('user_id') . '_' . time() . '_' . $i . '.' . $uploaded->getClientOriginalExtension();
                $filePath = $uploaded->storeAs('awards', $fileName, 'public');

                if ($award && $award->file_path && Storage::disk('public')->exists($award->file_path)) {
                    try { Storage::disk('public')->delete($award->file_path); } catch (\Exception $e) {}
                }
            } else {
                $filePath = $existing ?: ($award->file_path ?? null);
            }

            if ($award) {
                $award->award_name = $name;
                $award->file_path = $filePath;
                $award->save();
            } else {
                $award = AwardFile::create([
                    'resume_id' => $resume->id,
                    'award_name' => $name,
                    'file_path' => $filePath,
                ]);
            }

            if ($award && $award->id) $processedAwardIds[] = $award->id;
        }

        AwardFile::where('resume_id', $resume->id)
                 ->whereNotIn('id', $processedAwardIds)
                 ->get()
                 ->each(function ($award) {
                     if ($award->file_path && Storage::disk('public')->exists($award->file_path)) {
                         try { Storage::disk('public')->delete($award->file_path); } catch (\Exception $e) {}
                     }
                     $award->delete();
                 });
    }

    private function defaultResumeData($userId)
    {
        return [
            'fullname' => 'Irish Rivera',
            'dob' => 'January 17, 2005',
            'pob' => 'Tinga Itaas, Batangas City',
            'civil_status' => 'Single',
            'specialization' => 'Computer Science',
            'email' => '23-00679@g.batstate-u.edu.ph',
            'phone' => '09362695155',
            'address' => 'Tinga Itaas, Batangas City',
            'organization' => [
                'name' => ['ACCESS', 'JPCS'],
                'position' => ['Member', 'Member'],
                'year' => ['Present', 'Present']
            ],
            'interests' => ['Software Development','Artificial Intelligence','Web Development','Cybersecurity'],
            'education' => [
                ['level' => 'Elementary', 'school' => 'Tinga Itaas Elementary School', 'year' => '2017'],
                ['level' => 'Secondary', 'school' => 'STI - Batangas', 'year' => null],
                ['level' => 'Tertiary', 'school' => null, 'year' => null]
            ],
            'skills' => ['key' => ['Teamwork'], 'value' => ['Works effectively in group settings to achieve shared goals.']],
            'programming' => ['key' => ['Python'], 'value' => ['Used for automation, data analysis, and backend development.']],
            'projects' => [],
            'user_id' => $userId
        ];
    }

    private function validationRules()
    {
        return [
            'fullname' => 'nullable|string|max:255',
            'dob' => 'nullable|string|max:255',
            'pob' => 'nullable|string|max:255',
            // gender input (stored into civil_status column). Allowed options: female, male, prefer_not_to_say
            'gender' => 'nullable|in:female,male,prefer_not_to_say',
            'civil_status' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',

            'interests' => 'nullable|array',
            'interests.*' => 'nullable|string|max:30',

            'projects' => 'nullable|array',
            'projects.*' => 'nullable|string|max:255',

            'organization.name' => 'nullable|array',
            'organization.name.*' => 'nullable|string|max:255',
            'organization.position' => 'nullable|array',
            'organization.position.*' => 'nullable|string|max:255',
            'organization.year' => 'nullable|array',
            'organization.year.*' => 'nullable|string|max:50',

            'skills' => 'nullable|array',
            'skills.key' => 'nullable|array',
            'skills.key.*' => 'nullable|string|max:20',
            'skills.value' => 'nullable|array',
            'skills.value.*' => 'nullable|string|max:100',

            'programming' => 'nullable|array',
            'programming.key' => 'nullable|array',
            'programming.key.*' => 'nullable|string|max:15',
            'programming.value' => 'nullable|array',
            'programming.value.*' => 'nullable|string|max:50',
        ];
    }
}
