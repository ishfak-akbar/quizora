<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->latest()
            ->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:150',
            'body'     => 'required|string|max:2000',
            'audience' => 'required|in:all,teachers,students',
            'type'     => 'required|in:info,success,warning',
        ]);

        Announcement::create([
            ...$data,
            'created_by' => Auth::id(),
            'is_active'  => true,
        ]);

        return back()->with('success', 'Announcement published successfully.');
    }

    public function toggle(Announcement $announcement)
    {
        $announcement->update([
            'is_active' => !$announcement->is_active,
        ]);

        $msg = $announcement->is_active ? 'Announcement activated.' : 'Announcement deactivated.';
        return back()->with('success', $msg);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}
