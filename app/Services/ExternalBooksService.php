<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExternalBooksService
{

    /**
     * Search external api books
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function searchBooks(Request $request)
    {
        $query = $this->buildQuery($request);

        if (!$query) {
            return [];
        }
       
        //Cache query search for 2 hours, since there's a rate limit
        return Cache::remember($query, "7200", function () use ($query) {
                $response =  Http::get(env('OFFICE_API')."/books?${query}");
                return json_decode($response->getBody()->getContents(), true);
            });
    }

    /**
     * Build Request query
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function buildQuery($request)
    {
        $query = [];

        if ($request->name) {
            $query['name'] = $request->name;

        }

        if ($request->country) {
            $query['country'] = $request->country;
        }

        if ($request->publisher) {
            $query['publisher'] = $request->publisher;
        }

        if ($request->date) {
            $query['date'] = (int) $request->date;
        }

        return http_build_query($query);

    }
}