<?php

namespace App\Helpers;



/**
 * Class helper for response
 */
class ResponseHelper
{
    public function responseFormat($status, $message, $code = 200, $data = null)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if ($data) {
            $response['data'] = $data;
        }
        $count = 0;
        if (!is_null($data)) {
            $count = 1;
            if(is_array($data)) {
                $count = count($data);
            }
        }

        return response()->json($data, $code)
                        ->header('Content-Range', 'posts 0-9/'.$count)
                        ->header('access-control-expose-headers', 'Content-Range');
    }
}