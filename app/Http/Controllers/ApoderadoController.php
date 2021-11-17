<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Apoderado;
use Illuminate\Http\Request;

class ApoderadoController extends Controller
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
            $sigupAdmin = $jwtAuth->singupApoderado($correo, $password);
            return response()->json($sigupAdmin, 200);
        } else {
            return Response()->json(array('status' => 'error', 'message' => 'Faltan datos', 'code' => 400), 200);
        }
    }

    public function getApoderados(Request $request)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if (is_null($checkToken)) {
            $apoderados = Apoderado::all();
            return response()->json(array('apoderados' => $apoderados, 'status' => 'success', 'message' => 'Apoderados encontrados', 'code' => 200), 200);
        } else {
            return response()->json($checkToken, 200);
        }
    }

    public function getApoderadosLastName(Request $request)
    {
        $lastName = $request->query('lastName');
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if (is_null($checkToken)) {
            $apoderados = Apoderado::query()->where('lastname', 'like', '%' . $lastName . '%')->get();
            return response()->json(array('apoderados' => $apoderados, 'status' => 'success', 'message' => 'Apoderados encontrados', 'code' => 200), 200);
        } else {
            return response()->json($checkToken, 200);
        }
    }

    public function create(Request $request)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if (is_null($checkToken)) {
            //recibir datos
            $json = $request->input('json', null);
            $params = json_decode($json);
            $name = (!is_null($json) && isset($params->name)) ? $params->name : null;
            $lastname = (!is_null($json) && isset($params->lastname)) ? $params->lastname : null;
            $correo = (!is_null($json) && isset($params->correo)) ? $params->correo : null;
            $password = (!is_null($json) && isset($params->password)) ? $params->password : null;

            if (!is_null($name) && !is_null($lastname) && !is_null($correo) && !is_null($password)) {
                $proxie = new Apoderado();
                $proxie->name = $name;
                $proxie->lastname = $lastname;
                $proxie->correo = $correo;
                $proxie->password = $password;
                $proxie->save();
                return Response()->json(array('apoderado' => $proxie, 'status' => 'success', 'message' => 'Estudiante creado satisfactoriament', 'code' => 200), 200);
            } else {
                return Response()->json(array('status' => 'error', 'message' => 'Faltan datos', 'code' => 400), 200);
            }
        } else {
            return response()->json($checkToken, 200);
        }
    }

    public function update(Request $request,$id)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if (is_null($checkToken)) {
            //recibir datos
            $json = $request->input('json', null);
            $params = json_decode($json);
            $proxie = Apoderado::query()->where('id',$id)->update([
                'name' => $params->name,
                'lastname' =>$params->lastname,
                'correo' =>$params->correo,
                'password' =>$params->password,
            ]);
                return Response()->json(array('apoderado' => $proxie, 'status' => 'success', 'message' => 'Estudiante creado satisfactoriament', 'code' => 200), 200);

        } else {
            return response()->json($checkToken, 200);
        }
    }


}
