<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string|max:255',
            'icon'     => 'required|string|max:255',
            'items'    => 'required|string',
        ]);

        Skill::create($data);

        return back()->with('success', 'Skill berhasil ditambahkan!');
    }

    public function update(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'category' => 'required|string|max:255',
            'icon'     => 'required|string|max:255',
            'items'    => 'required|string',
        ]);

        $skill->update($data);

        return back()->with('success', 'Skill berhasil diperbarui!');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return back()->with('success', 'Skill berhasil dihapus!');
    }
}
