<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Niveles;
use Illuminate\Http\Request;

class NivelesController extends Controller
{
    public function getNiveles(Request $request)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if (is_null($checkToken)) {
            $niveles = Niveles::all();
            return response()->json(array('niveles' => $niveles, 'status' => 'success'), 200);
        } else {
            return response()->json($checkToken, 200);
        }
    }
}
