<?php

namespace App\Http\Controllers;

use App\Http\Resources\BooksResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BooksController extends Controller
{
    /**
     * Create book
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'string|required',
            'isbn' => 'string|required',
            'authors' => 'required|array',
            'authors.*' => 'string',
            'country' => 'string|required',
            'number_of_pages' => 'integer|required',
            'publisher' => 'string|required',
            'release_date' => 'date'
        ]);

        try {
            $book = Book::create($validated);
            return $this->jsonResponse([
                "book" => new BooksResource($book->toArray())
            ], Response::HTTP_CREATED, "success");
            
        } catch (\Exception $e) {
            return $this->jsonResponse([], 
            Response::HTTP_BAD_REQUEST, 
            "error", $e->getMessage());
        }
    }

    /**
     * Fetch all books
     * @return \Illuminate\Http\JsonResponse
     */
    public function read()
    {
        $books = Book::all();

        return $this->jsonResponse(
            BooksResource::collection($books->toArray())
        );
    }

    /**
     * Update book
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Book $book)
    {
        $validated = $this->validate($request, [
            'name' => 'string',
            'isbn' => 'string',
            'country' => 'string',
            'authors' => 'array|required_array_keys:0',
            'authors.*' => 'string',
            'numberOfPages' => 'integer',
            'publisher' => 'string',
            'released' => 'date'
        ], [
            "authors.required_array_keys" => "The authors should not be empty"
        ]);
        
    
        try {
            $title = $book->name;
            $book->update($validated);
            return $this->jsonResponse(
                new BooksResource($book->toArray()),
                Response::HTTP_OK,
                "success",
                "The book {$title} was updated successfully"
            );
        } catch (\Exception $e) {
            return $this->jsonResponse([], 
            Response::HTTP_BAD_REQUEST, 
            "error",
            $e->getMessage()
        );
        }

        
    }

    /**
     * Delete book by ID
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Book $book)
    {

        try {
            $book->delete();

            return $this->jsonResponse(
                [], 
                Response::HTTP_NO_CONTENT, 
                "success", 
                "The book '{$book->name}' was deleted successfully"
            );
        } catch (\Exception $e) {
            return $this->jsonResponse([], 
            Response::HTTP_BAD_REQUEST, 
            "error",
            $e->getMessage()
            );
        }
    }

    /**
     * Fetch Book by ID
     * @var Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Book $book)
    {
        return $this->jsonResponse(
            new BooksResource($book->toArray())
        );
    }
}
