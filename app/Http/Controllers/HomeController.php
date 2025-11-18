<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('user_id');
        $userName = session('user_name', 'User');

        if (!$userId) {
            return redirect('/login')->with('error', 'Please log in to access your homepage.');
        }

        $resume = Resume::with('awardFiles')->where('user_id', $userId)->first();

        if ($resume) {
            $resume->awardFiles = $resume->awardFiles ?? collect();
            $resume->interests = is_array($resume->interests)
                ? $resume->interests
                : (is_string($resume->interests) ? json_decode($resume->interests, true) ?? [] : []);

            $resume->projects = is_array($resume->projects)
                ? $resume->projects
                : (is_string($resume->projects) ? json_decode($resume->projects, true) ?? [] : []);
        }

        return view('home', compact('userName', 'resume'));
    }
}
