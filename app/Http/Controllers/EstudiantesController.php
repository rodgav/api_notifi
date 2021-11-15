<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use Illuminate\Http\Request;

class EstudiantesController extends Controller
{
    public function login(Request $request)
    {
        $jwtAuth = new JwtAuth();
        //recibir datos
        $json = $request->input('json', null);
        $params = json_decode($json);
        $correo = (!is_null($json) && isset($params->correo)) ? $params->correo : null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        if (!is_null($correo) && !is_null($password)) {
            $sigupAdmin = $jwtAuth->singupEstudiante($correo, $password);
            if (!is_null($sigupAdmin)) {
                return response()->json(array('token' => $sigupAdmin), 200);
            }
            return Response()->json(array('status' => 'error', 'message' => 'Login incorrecto', 'code' => 400), 200);
        } else {
            return Response()->json(array('status' => 'error', 'message' => 'Faltan datos', 'code' => 400), 200);
        }
    }
}
