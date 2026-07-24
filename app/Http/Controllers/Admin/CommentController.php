<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = PostComment::with(['post', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }

        if ($request->filled('search')) {
            $query->where('comment', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $comments = $query->paginate(25);

        return view('admin.comments.index', compact('comments'));
    }

    public function edit($id)
    {
        $comment = PostComment::with(['post', 'user'])->findOrFail($id);
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $comment = PostComment::findOrFail($id);

        $validated = $request->validate([
            'comment' => 'required|string',
            'is_approved' => 'boolean',
        ]);

        $comment->update($validated);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment updated successfully.');
    }

    public function destroy($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment deleted successfully.');
    }

    public function approve($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->approve();

        return redirect()->back()->with('success', 'Comment approved successfully.');
    }
}
