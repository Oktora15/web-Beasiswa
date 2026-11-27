<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = auth()->user()->bookmarks()->with('scholarship')->latest()->paginate(10);

        return view('bookmarks.index', compact('bookmarks'));
    }

    public function toggle(Scholarship $scholarship)
    {
        abort_unless($scholarship->is_active, 404);
        $bookmark = auth()->user()->bookmarks()->firstOrCreate(['scholarship_id' => $scholarship->id]);
        $created = $bookmark->wasRecentlyCreated;

        if (! $created) {
            $bookmark->delete();
        }

        return back()->with('status', $created ? 'Beasiswa disimpan.' : 'Beasiswa dihapus dari simpanan.');
    }
}
