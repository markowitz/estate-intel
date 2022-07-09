<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BooksResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        $data = [
            "name" => $this["name"],
            "isbn" => $this["isbn"],
            "authors" => array_values($this["authors"]),
            "number_of_pages" => isset($this["numberOfPages"]) ? $this["numberOfPages"] : $this['number_of_pages'],
            "publisher" => $this["publisher"],
            "country" => $this["country"],
            "release_date" => isset($this['released']) ?
                             Carbon::parse($this["released"])->format("Y-m-d") : Carbon::parse($this["release_date"])->format("Y-m-d")
        ];

        if (isset($this['id'])) {
            $data = ['id' => $this['id']] + $data;
        }


        return $data;
    }
}
