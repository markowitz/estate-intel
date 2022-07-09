<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;

class BooksControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected $data;


    public function setUp(): void
    {
        parent::setUp();
        $this->initDatabase();

        $this->data =  [
            'id' => 1,
            'name' => $this->faker->sentence(3),
            'isbn' => $this->faker->ean13(),
            'authors' => [$this->faker->name()],
            'country' => $this->faker->country(),
            'number_of_pages' => $this->faker->randomNumber(2),
            'publisher' => $this->faker->sentence(2),
            'release_date' => $this->faker->date()
        ];
    }

    public function tearDown(): void
    {
        $this->resetDatabase();
        parent::tearDown();
    }

    /**
     * Test adding book to db
     *
     * @return void
     */
    public function test_create_book_success()
    {
        
        return $this->json('POST', '/api/books', $this->data)
                        ->assertExactJson([
                            "status_code" => Response::HTTP_CREATED,
                            "status" => "success",
                            "data" => ["book" => $this->data]
                        ]);

        
    }

    /**
     * Test adding book validation
     */
    public function test_create_book_validation()
    {
        $data = [];

        return $this->json('POST', '/api/books', $data)
                    ->assertUnprocessable();

    }

    /**
     * test get all books
     */
    public function test_read_books_endpoint()
    {
        $this->json('POST', '/api/books', $this->data);

        return $this->json('GET', '/api/books')
                ->assertSimilarJson([
                    "status_code" => Response::HTTP_OK,
                    "status" => "success",
                    "data" => [
                        $this->data
                    ]
                ]);
    }

    /**
     * test update book endpoint
     */
    public function test_patch_book()
    {
        $this->json('POST', '/api/books', $this->data);

        $data = [
            'name' => 'My First Updated Book'
        ];
        
        $title = $this->data['name'];
        $this->data['name'] = $data['name'];

        $this->json('PATCH', '/api/books/1', $data)
            ->assertExactJson([
                'status_code' => Response::HTTP_OK,
                "status" => "success",
                "message" => "The book $title was updated successfully",
                "data" => $this->data
            ]);
            
            //Test :id not in db
        $this->json('PATCH', '/api/books/3', $data)
                ->assertNotFound();
    }

    /**
     * Test delete book endpoint
     */
    public function test_delete_book()
    {
        $this->json('POST', '/api/books', $this->data);

        $title = $this->data['name'];

        $this->json('DELETE', '/api/books/1')
                ->assertExactJson([
                    "status_code" => Response::HTTP_NO_CONTENT,
                    "status" => "success",
                    "message" => "The book '$title' was deleted successfully",
                    "data" => []
                ]);
        
        //Test :id not in db
        $this->json('DELETE', '/api/books/3')
                ->assertNotFound();
    }

    /**
     * Test View book endpoint
     */
    public function test_show_book()
    {
        $this->json('POST', '/api/books', $this->data);

        $this->json('GET', '/api/books/1')
                    ->assertExactJson([
                        "status_code" => Response::HTTP_OK,
                        "status" => "success",
                        "data" => $this->data
                    ]);
        
        //Test :id not in db
        $this->json('GET', '/api/books/2')
            ->assertNotFound();         
    }
  
}
