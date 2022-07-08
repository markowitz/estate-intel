<?php

namespace App\Http\Controllers;

use App\Http\Resources\BooksResource;
use App\Services\ExternalBooksService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExternalBooksController extends Controller
{
    /**
     * @var \App\Services\ExternalBooksServices
     */
    protected $externalBooksService;
    
    public function __construct(ExternalBooksService $externalBooksService)
    {
        $this->externalBooksService = $externalBooksService;
    }

    /**
     * Search External Books
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchBooks(Request $request)
    {
        $data = $this->externalBooksService->searchBooks($request);
        
        if (empty($data)) {
            return $this->jsonResponse([], Response::HTTP_NOT_FOUND, "not found");
        }
        
        return $this->jsonResponse(BooksResource::collection($data));
    }
}
