<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;
use App\Models\AwardFile;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    // ==================== PUBLIC RESUME PAGE ==================== //
    public function showPublicResume()
    {
        $resume = Resume::with('awardFiles')->latest()->first();
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

    // ==================== WELCOME PAGE WITH PREVIEW ==================== //
    public function publicResume()
    {
        $resume = Resume::with('awardFiles')->latest()->first();
        $resume->awardFiles = $resume->awardFiles ?? collect();

        return view('welcome', compact('resume'));
    }

    // ==================== VIEW LOGGED-IN USER RESUME ==================== //
    public function resume()
    {
        if (!AuthController::checkAuth()) return redirect('/login');

        $userId = session('user_id');
        $resume = Resume::firstOrCreate(
            ['user_id' => $userId],
            $this->defaultResumeData($userId)
        );

        $resume->load('awardFiles');
        $resume->awardFiles = $resume->awardFiles ?? collect();
        $resume->interests = is_array($resume->interests) ? $resume->interests : [];
        $resume->projects = is_array($resume->projects) ? $resume->projects : [];
        $resume->organization = is_array($resume->organization) ? $resume->organization : ['name' => [], 'position' => [], 'year' => []];
        $resume->skills = is_array($resume->skills) ? $resume->skills : ['key' => [], 'value' => []];
        $resume->programming = is_array($resume->programming) ? $resume->programming : ['key' => [], 'value' => []];

        return view('resume', compact('resume'));
    }

    // ==================== SHOW EDIT FORM ==================== //
    public function editResume($id)
    {
        if (!AuthController::checkAuth()) return redirect('/login');

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

    // ==================== STORE RESUME ==================== //
    public function storeResume(Request $request)
    {
        if (!AuthController::checkAuth()) return redirect('/login');

        // Validation rules (frontend constraints mirrored on server)
        $rules = [
            'fullname' => 'nullable|string|max:255',
            'dob' => 'nullable|string|max:255',
            'pob' => 'nullable|string|max:255',
            'civil_status' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',

            // arrays
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

            // skills: key/value arrays
            'skills' => 'nullable|array',
            'skills.key' => 'nullable|array',
            'skills.key.*' => 'nullable|string|max:20',
            'skills.value' => 'nullable|array',
            'skills.value.*' => 'nullable|string|max:100',

            // programming: key/value arrays
            'programming' => 'nullable|array',
            'programming.key' => 'nullable|array',
            'programming.key.*' => 'nullable|string|max:15',
            'programming.value' => 'nullable|array',
            'programming.value.*' => 'nullable|string|max:50',
        ];

        $validated = $request->validate($rules);

        $resume = new Resume();
        $resume->user_id = session('user_id');

        $this->saveResumeData($resume, $request);
        $resume->save();

        $this->saveAwards($resume, $request);

        return redirect('/resume')->with('success', 'Resume created successfully!');
    }

    // ==================== UPDATE RESUME ==================== //
    public function updateResume(Request $request, $id)
    {
        if (!AuthController::checkAuth()) return redirect('/login');

        // Validation rules same as store
        $rules = [
            'fullname' => 'nullable|string|max:255',
            'dob' => 'nullable|string|max:255',
            'pob' => 'nullable|string|max:255',
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

        $validated = $request->validate($rules);

        $resume = Resume::where('id', $id)
                        ->where('user_id', session('user_id'))
                        ->firstOrFail();

        // Handle cropped profile image (data URL)
        if ($request->cropped_image) {
            if ($resume->profile_photo && Storage::disk('public')->exists($resume->profile_photo)) {
                Storage::disk('public')->delete($resume->profile_photo);
            }

            if (preg_match('/^data:(image\/\w+);base64,/', $request->cropped_image, $matches)) {
                $image = $request->cropped_image;
                $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'resume_' . session('user_id') . '_' . time() . '.png';
                Storage::disk('public')->put('resume_photos/' . $imageName, base64_decode($image));
                $resume->profile_photo = 'resume_photos/' . $imageName;
            }
        }

        $this->saveResumeData($resume, $request);
        $resume->save();

        $this->saveAwards($resume, $request);

        return redirect('/resume')->with('success', 'Resume updated successfully!');
    }

    // ==================== DELETE RESUME ==================== //
    public function deleteResume($id)
    {
        if (!AuthController::checkAuth()) return redirect('/login');

        $resume = Resume::where('id', $id)
                        ->where('user_id', session('user_id'))
                        ->firstOrFail();

        if ($resume->profile_photo && Storage::disk('public')->exists($resume->profile_photo)) {
            Storage::disk('public')->delete($resume->profile_photo);
        }

        foreach ($resume->awardFiles as $award) {
            if ($award->file_path && Storage::disk('public')->exists($award->file_path)) {
                Storage::disk('public')->delete($award->file_path);
            }
            $award->delete();
        }

        $resume->delete();

        return redirect('/resume')->with('success', 'Resume deleted successfully!');
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
        $resume->civil_status = $request->civil_status;
        $resume->specialization = $request->specialization;
        $resume->email = $request->email;
        $resume->phone = $request->phone;
        $resume->address = $request->address;

        $resume->organization = [
            'name' => $request->input('organization.name', []),
            'position' => $request->input('organization.position', []),
            'year' => $request->input('organization.year', []),
        ];

        $resume->interests = $request->interests ?? [];
        $resume->education = $request->education ?? [];
        $resume->skills = $request->skills ?? [];
        $resume->programming = $request->programming ?? [];
        $resume->projects = $request->projects ?? [];
    }

    // ==================== SAVE AND SYNC AWARDS ==================== //
    private function saveAwards($resume, $request)
    {
        // incoming arrays from the form (blade sends these names)
        $awardNames     = $request->input('awards.name', []);
        $existingFiles  = $request->input('awards.existing_file', []); // storage-relative paths or empty
        $uploadedFiles  = $request->file('awards.file') ?? [];

        $processedAwardIds = [];

        // Use max count to cover all indices
        $max = max(count($awardNames), count($existingFiles), count($uploadedFiles));

        for ($i = 0; $i < $max; $i++) {
            $name = isset($awardNames[$i]) ? trim($awardNames[$i]) : null;
            $existing = isset($existingFiles[$i]) ? $existingFiles[$i] : null;
            $uploaded = isset($uploadedFiles[$i]) ? $uploadedFiles[$i] : null;

            // Skip empty rows
            if (empty($name) && empty($existing) && empty($uploaded)) {
                continue;
            }

            // Try to find an existing AwardFile for this resume & name
            $award = AwardFile::where('resume_id', $resume->id)
                              ->where('award_name', $name)
                              ->first();

            $filePath = null;

            if ($uploaded && $uploaded instanceof \Illuminate\Http\UploadedFile) {
                // Save uploaded file
                $fileName = 'award_' . session('user_id') . '_' . time() . '_' . $i . '.' . $uploaded->getClientOriginalExtension();
                $filePath = $uploaded->storeAs('awards', $fileName, 'public');

                // Delete old stored file if replacing
                if ($award && $award->file_path && Storage::disk('public')->exists($award->file_path)) {
                    try {
                        Storage::disk('public')->delete($award->file_path);
                    } catch (\Exception $e) {
                        // ignore
                    }
                }
            } else {
                // No new upload: preserve existing hidden input value if provided
                if (!empty($existing)) {
                    $filePath = $existing;
                } else {
                    // if award exists and has a file, preserve it
                    if ($award && $award->file_path) {
                        $filePath = $award->file_path;
                    } else {
                        $filePath = null;
                    }
                }
            }

            if ($award) {
                // Update existing
                $award->award_name = $name;
                $award->file_path = $filePath;
                $award->save();
            } else {
                // Create new award
                $award = AwardFile::create([
                    'resume_id' => $resume->id,
                    'award_name' => $name,
                    'file_path' => $filePath,
                ]);
            }

            if ($award && $award->id) {
                $processedAwardIds[] = $award->id;
            }
        }

        // Delete awards not submitted
        AwardFile::where('resume_id', $resume->id)
                 ->whereNotIn('id', $processedAwardIds)
                 ->get()
                 ->each(function ($award) {
                     if ($award->file_path && Storage::disk('public')->exists($award->file_path)) {
                         try {
                             Storage::disk('public')->delete($award->file_path);
                         } catch (\Exception $e) {
                             // ignore
                         }
                     }
                     $award->delete();
                 });
    }

    // ==================== DEFAULT RESUME DATA ==================== //
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
            'interests' => [
                'Software Development',
                'Artificial Intelligence',
                'Web Development',
                'Cybersecurity'
            ],
            'education' => [
                ['level' => 'Elementary', 'school' => 'Tinga Itaas Elementary School', 'year' => '2017'],
                ['level' => 'Secondary', 'school' => 'STI - Batangas', 'year' => null],
                ['level' => 'Tertiary', 'school' => null, 'year' => null]
            ],
            'skills' => [
                'key' => ['Teamwork'],
                'value' => ['Works effectively in group settings to achieve shared goals.']
            ],
            'programming' => [
                'key' => ['Python'],
                'value' => ['Used for automation, data analysis, and backend development.']
            ],
            'projects' => [],
            'user_id' => $userId
        ];
    }
}
