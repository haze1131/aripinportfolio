<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = 'storage/' . $request->file('image')->store('uploads', 'public');
        }

        Project::create($data);

        return back()->with('success', 'Project berhasil ditambahkan!');
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $project->image));
            }
            $data['image'] = 'storage/' . $request->file('image')->store('uploads', 'public');
        } else {
            unset($data['image']);
        }

        $project->update($data);

        return back()->with('success', 'Project berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $project->image));
        }

        $project->delete();

        return back()->with('success', 'Project berhasil dihapus!');
    }
}
