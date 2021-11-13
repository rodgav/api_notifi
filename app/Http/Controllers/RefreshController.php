<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use Illuminate\Http\Request;

class RefreshController extends Controller
{
    public function refresh(Request $request){
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $refreshToken = $jwtAuth->refresh($token);
        return response()->json($refreshToken, 200);
    }
}
