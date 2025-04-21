<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index() {
        $comments = Comment::where('status', 'pending')->get();
        return view('admin.comment.index', compact('comments'));
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->status = 'approved';
        $comment->save();

        return redirect()->back()->with('success', 'Komentar telah disetujui.');
    }

    public function delete($id)
    {
        Comment::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Komentar telah dihapus.');
    }
}
