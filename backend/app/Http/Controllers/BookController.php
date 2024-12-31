<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Like;
use Illuminate\Http\Request;


class BookController extends Controller
{
    public function index()
    {
        return response()->json(Book::all(), 200);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn' => 'required|unique:books',
            'judul' => 'required',
            'penulis' => 'required',
            'genre' => 'required',
            'deskripsi' => 'required',
            'foto' => 'nullable|file|mimes:png,jpg,jpeg',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('books', 'public');
            $validated['foto'] = $path;
        }

        $book = Book::create($validated);

        return response()->json($book, 201);
    }

    public function show($id)
    {
        $book = Book::withCount('likes')->findOrFail($id);
        return response()->json([
            'book' => $book,
        ]);
    }

    public function edit(Book $book)
    {

    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan']. 404);
        }

        $validated = $request->validate([
            'isbn' => 'required|unique:books,isbn'.$id,
            'judul' => 'required',
            'penulis' => 'required',
            'genre' => 'required',
            'deskripsi' => 'required',
            'foto' => 'nullable|file|mimes:png,jpg,jpeg'
        ]);

        if ($request->hasFile('foto')) {
            if ($book->foto && \Storage::exists('public/'.$book->foto)) {
                \Storage::delete('public/'.$book->foto);
            }

            $path = $request->file('foto')->storage('books', 'public');
            $validated['foto'] = $path;
        }

        $book->update($validated);

        return response()->json($book, 200);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan.'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Buku sudah dihapus!'], 200);
    }

    public function like(Request $request, $id) {
        $book = Book::findOrFail($id);
        $user = $request->user();

        if ($user->likes()->where('book_id', $book->id)->exists()) {
            return response()->json(['message' => 'Anda sudah menyukai buku ini']. 400);
        }

        $user->likes()->create(['book_id' => $book->id]);

        return response()->json(['message' => 'Liked successfully']);
    }

    public function unlike($id) {
        $like = Like::where('user_id', auth()->id())->where('book_id', $id)->first();

        if (!$like) {
            return response()->json(['message' => 'Anda belum menyukai buku ini'], 400);
        }

        $like->delete();

        return response()->json(['message' => 'Unliked successfully']);
    }
}
