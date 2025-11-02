<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;

class ResumeController extends Controller
{
    // ==================== PUBLIC RESUME PAGE (for welcome "View My Resume") ==================== //
    public function showPublicResume()
    {
        $resume = Resume::latest()->first();
        return view('public_resume', compact('resume'));
    }

    // ==================== WELCOME PAGE WITH PREVIEW ==================== //
    public function publicResume()
    {
        $resume = Resume::latest()->first();
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

        $this->decodeJsonFields($resume);
        return view('resume', compact('resume'));
    }

    // ==================== SHOW EDIT FORM ==================== //
    public function editResume($id)
    {
        if (!AuthController::checkAuth()) return redirect('/login');

        $resume = Resume::where('id', $id)
                        ->where('user_id', session('user_id'))
                        ->firstOrFail();

        $this->decodeJsonFields($resume);
        return view('edit_resume', compact('resume'));
    }

    // ==================== STORE RESUME ==================== //
    public function storeResume(Request $request)
    {
        if (!AuthController::checkAuth()) return redirect('/login');

        $resume = new Resume();
        $resume->user_id = session('user_id');
        $this->saveResumeData($resume, $request);
        $resume->save();

        return redirect('/resume')->with('success', 'Resume created successfully!');
    }

    // ==================== UPDATE RESUME ==================== //
    public function updateResume(Request $request, $id)
    {
        if (!AuthController::checkAuth()) return redirect('/login');

        $resume = Resume::where('id', $id)
                        ->where('user_id', session('user_id'))
                        ->firstOrFail();

        $this->saveResumeData($resume, $request);
        $resume->save();

        return redirect('/resume')->with('success', 'Resume updated successfully!');
    }

    // ==================== DELETE RESUME ==================== //
    public function deleteResume($id)
    {
        if (!AuthController::checkAuth()) return redirect('/login');

        $resume = Resume::where('id', $id)
                        ->where('user_id', session('user_id'))
                        ->firstOrFail();

        $resume->delete();
        return redirect('/resume')->with('success', 'Resume deleted successfully!');
    }

    // ==================== DOWNLOAD RESUME ==================== //
    public function downloadResume($id = null)
    {
        $resume = $id ? Resume::find($id) : Resume::latest()->first();
        if (!$resume) return redirect('/')->with('error', 'Resume not found.');

        return response()->view('resume.resume', compact('resume'))
                        ->header('Content-Type', 'text/html')
                        ->header('Content-Disposition', 'attachment; filename="' . ($resume->fullname ?? 'Resume') . '.html"');
    }

    // ==================== HELPER FUNCTIONS ==================== //
    private function decodeJsonFields(&$resume)
    {
        $fields = ['organization','education','skills','programming','interests','awards','projects'];
        foreach ($fields as $field) {
            if (is_string($resume->$field)) {
                $resume->$field = json_decode($resume->$field, true);
            }
        }
    }

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

        $resume->organization = json_encode([
            'name' => $request->input('organization.name', []),
            'position' => $request->input('organization.position', []),
            'year' => $request->input('organization.year', []),
        ]);

        $resume->interests = json_encode($request->interests ?? []);
        $resume->education = json_encode($request->education ?? []);
        $resume->skills = json_encode($request->skills ?? []);
        $resume->programming = json_encode($request->programming ?? []);
        $resume->projects = json_encode($request->projects ?? []);
        $resume->awards = json_encode($request->awards ?? []);
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
            'organization' => json_encode([
                "name" => ["ACCESS", "JPCS"],
                "position" => ["Member", "Member"],
                "year" => ["Present", "Present"]
            ]),
            'interests' => json_encode([
                "Software Development",
                "Artificial Intelligence",
                "Web Development",
                "Cybersecurity"
            ]),
            'education' => json_encode([
                ["level" => "Elementary", "school" => "Tinga Itaas Elementary School", "year" => "2017"],
                ["level" => "Secondary", "school" => "STI - Batangas", "year" => null],
                ["level" => "Tertiary", "school" => null, "year" => null]
            ]),
            'skills' => json_encode([
                "Teamwork" => "Works effectively in group settings to achieve shared goals."
            ]),
            'programming' => json_encode([
                'Python' => 'Used for automation, data analysis, and backend development.'
            ]),
            'projects' => json_encode([]),
            'awards' => json_encode([]),
            'user_id' => $userId
        ];
    }
}
