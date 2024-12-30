<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reply;

class ReplyController extends Controller
{
    public function index($bookId) {
        $replies = Reply::where('book_id', $bookId)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($replies);
    }

    public function store(Request $request) {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'content' => 'required'
        ]);

        $reply = Reply::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'content' => $request->content,
        ]);

        return response()->json($reply, 201);
    }

    public function show($id) {
        $reply = Reply::with('user:id,name')->find($id);

        if (!$reply) {
            return response()->json(['message' => 'Komentar tidak ditemukan'], 404);
        }

        return response()->json($reply);
    }

    public function update(Request $request, $id) {
        $reply = Reply::find($id);

        if (!$reply) {
            return response()->json(['message' => 'Komentar tidak ditemukan'], 404);
        }

        if ($reply->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required',
        ]);

        $reply->update([
            'content' => $request->content,
        ]);

        return response()->json($reply);
    }

    public function destroy($id) {
        $reply = Reply::find($id);

        if (!$reply) {
            return response()->json(['message' => 'Komentar tidak ditemukan'], 404);
        }

        if ($reply->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reply->delete();
        return response()->json(['message' => 'Reply deleted successfully']);
    }
}
