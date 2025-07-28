<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Comment::with(['post', 'post.category'])
                       ->latest();
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $comments = $query->paginate(15);
        $pendingCount = Comment::where('status', 'pending')->count();
        
        return view('admin.comments.index', compact('comments', 'status', 'pendingCount'));
    }

    public function approve(Comment $comment)
    {
        $comment->approve();
        
        return back()->with('success', 'Comentario aprobado exitosamente.');
    }

    public function reject(Comment $comment)
    {
        $comment->reject();
        
        return back()->with('success', 'Comentario rechazado exitosamente.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        
        return back()->with('success', 'Comentario eliminado exitosamente.');
    }
}
