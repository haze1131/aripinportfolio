<?php

namespace App\Http\Controllers;

use App\Models\PortfolioContent;
use App\Models\Project;
use App\Models\Skill;

class HomeController extends Controller
{
    public function index()
    {
        $c = PortfolioContent::all()->keyBy('key');
        $projects = Project::latest()->get();
        $skills = Skill::oldest()->get();

        // These sections are supported by the front-end template but do not yet
        // have database tables/admin CRUD in this project.
        $experiences = collect();
        $certificates = collect();
        $posts = collect();
        $testimonials = collect();

        return view('welcome', compact(
            'c',
            'projects',
            'skills',
            'experiences',
            'certificates',
            'posts',
            'testimonials'
        ));
    }
}
