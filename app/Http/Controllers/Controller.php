<?php

namespace App\Http\Controllers;


use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    ###########################################################
    public function signResponse($msg,$user, $token,$status):JsonResponse
    {
        return response()->json([
            'message' => $msg,
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            'status' => true,
            'code' => $status,
        ], $status);
    }
    ###########################################################
    public function errorResponse($msg):JsonResponse
    {
        return response()->json([
            'message' => $msg,
            'data' => [],
            'status' => false,
            'code' => Response::HTTP_NOT_FOUND,
        ], Response::HTTP_NOT_FOUND);
    }
    public function okResponse($msg,$data):JsonResponse
    {
        return response()->json([
            'message' => $msg,
            'data' => $data,
            'status' => true,
            'code' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
    ###########################################################
    public function socialiteResponse($msg,$id,$name,$email,$image,$google_token,$sanctum_token):JsonResponse
    {
        return response()->json([
            "message"=>"login with ".$msg,
            "data" => [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'image' => $image,
                'google_token' => $google_token,
                'sanctum_token' => $sanctum_token,
            ],
            'status' => true,
            'code'=>Response::HTTP_OK
        ],Response::HTTP_OK);
    }
    ###########################################################
    public function paginateResponse($data)
    {
        $dataFetched = $data->items();

        $links = [
            'first' => $data->url(1),
            'last' => $data->url($data->lastPage()),
            'next' => $data->nextPageUrl(),
            'prev' => $data->previousPageUrl(),
        ];

        $meta = [
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem(),
        ];

        return response()->json([
            'message' => 'data fetched successfully',
            'data' => $dataFetched,
            'links' => $links,
            'meta' => $meta,
            'status' => true,
            'code' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
}
