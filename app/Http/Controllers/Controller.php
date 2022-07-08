<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * format Json Response
     * @param mixed $data
     * @param int $response
     * @param string $status
     * @param mixed $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonResponse($data = [], $response = Response::HTTP_OK, $status = "success", $message = null)
    {
        $responseData = [
            "status_code" => $response,
            "status" => $status,
            "data" => $data
        ];

        if ($message) {
            $responseData["message"] = $message;
        }
        krsort($responseData);

        return response()->json($responseData);
    }
}
