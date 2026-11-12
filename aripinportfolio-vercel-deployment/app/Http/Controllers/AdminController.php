<?php

namespace App\Http\Controllers;

use App\Models\PortfolioContent;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $contents = PortfolioContent::all()->keyBy('key');
        $projects = Project::latest()->get();
        $skills = Skill::latest()->get();

        return view('admin.dashboard', compact('contents', 'projects', 'skills'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'home_name' => ['nullable', 'string', 'max:100'],
            'home_title' => ['nullable', 'string', 'max:150'],
            'home_description' => ['nullable', 'string', 'max:5000'],
            'about_description' => ['nullable', 'string', 'max:10000'],
            'about_quote' => ['nullable', 'string', 'max:2000'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'home_photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'about_photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            // Kept for compatibility with the original admin dashboard form.
            'about_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $allowedKeys = [
            'home_name',
            'home_title',
            'home_description',
            'about_description',
            'about_quote',
            'contact_email',
            'contact_phone',
        ];

        DB::transaction(function () use ($request, $validated, $allowedKeys) {
            foreach ($allowedKeys as $key) {
                if (array_key_exists($key, $validated)) {
                    PortfolioContent::updateOrCreate(
                        ['key' => $key],
                        ['value' => $validated[$key] ?? '']
                    );
                }
            }

            $uploads = [
                'home_photo_file' => 'home_photo',
                'about_photo_file' => 'about_photo',
                'about_image_file' => 'about_photo',
            ];

            foreach ($uploads as $field => $contentKey) {
                if (!$request->hasFile($field)) {
                    continue;
                }

                $path = $request->file($field)->store('portfolio', 'public');
                $value = 'storage/' . $path;

                PortfolioContent::updateOrCreate(
                    ['key' => $contentKey],
                    ['value' => $value]
                );

                // Keep the legacy key in sync for the original admin preview.
                if ($field === 'about_image_file') {
                    PortfolioContent::updateOrCreate(
                        ['key' => 'about_image'],
                        ['value' => $value]
                    );
                }
            }
        });

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
