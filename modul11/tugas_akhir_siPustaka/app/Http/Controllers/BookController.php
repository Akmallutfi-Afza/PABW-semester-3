<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Book::latest()->get());
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'local');
        }

        $book = Book::create($data);

        return response()->json($book, 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json($this->findBook($id));
    }

    public function update(UpdateBookRequest $request, string $id): JsonResponse
    {
        $book = $this->findBook($id);
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            if ($book->cover) {
                Storage::disk('local')->delete($book->cover);
            }

            $data['cover'] = $request->file('cover')->store('covers', 'local');
        }

        $book->update($data);

        return response()->json($book);
    }

    public function destroy(string $id): JsonResponse
    {
        $book = $this->findBook($id);

        if ($book->cover) {
            Storage::disk('local')->delete($book->cover);
        }

        $book->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus',
        ]);
    }

    public function cover(string $id)
    {
        $book = $this->findBook($id);

        if (! $book->cover || ! Storage::disk('local')->exists($book->cover)) {
            abort(404, 'Sampul buku tidak ditemukan');
        }

        return Storage::disk('local')->response($book->cover);
    }

    private function findBook(string $id): Book
    {
        $book = Book::find($id);

        if (! $book) {
            abort(404, 'Buku tidak ditemukan');
        }

        return $book;
    }
}
