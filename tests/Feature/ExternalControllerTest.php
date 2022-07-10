<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;

class ExternalControllerTest extends TestCase
{
    protected $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = [
            [
                "name" => "A Game of Thrones",
                "isbn" => "978-0553103540",
                "authors" => [
                    "George R. R. Martin"
                ],
                "number_of_pages" => 694,
                "publisher" => "Bantam Books",
                "country" => "United States",
                "release_date" => "1996-08-01"
            ]
        ];
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_search_external_books()
    {   
        Http::fake([
            env('OFFICE_API').'/*' => Http::response($this->data)
        ]);

        $this->json('GET', '/api/external-books?name=A Game of Thrones')
            ->assertExactJson([
                "status_code" => Response::HTTP_OK,
                "status" => "success",
                "data" => $this->data
            ]);
    }

    public function test_search_external_books_no_data()
    {
        Http::fake([
            env('OFFICE_API').'/*' => Http::response([])
        ]);
        
        $this->json('GET', '/api/external-books?country=United Statess')
                    ->assertExactJson([
                        "status_code" => Response::HTTP_NOT_FOUND,
                        "status" => "not found",
                        "data" => []
                    ]);
    }
}