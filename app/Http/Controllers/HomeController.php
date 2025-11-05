<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get user info from session
        $userId = session('user_id');
        $userName = session('user_name', 'User');

        // If no user is logged in, redirect to login
        if (!$userId) {
            return redirect('/login')->with('error', 'Please log in to access your homepage.');
        }

        // Fetch the resume of the logged-in user
        $resume = Resume::with('awardFiles')->where('user_id', $userId)->first();

        // Ensure arrays are properly formatted
        if ($resume) {
            $resume->awardFiles = $resume->awardFiles ?? collect();
            $resume->interests = is_array($resume->interests)
                ? $resume->interests
                : (is_string($resume->interests) ? json_decode($resume->interests, true) ?? [] : []);

            $resume->projects = is_array($resume->projects)
                ? $resume->projects
                : (is_string($resume->projects) ? json_decode($resume->projects, true) ?? [] : []);
        }

        // Return the home view with username + resume
        return view('home', compact('userName', 'resume'));
    }
}
