<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Estudiantes;
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

    public function getEstudiantesNoApoderado(Request $request)
    {
        $idSubNivel = $request->query('idSubNivel');
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if (is_null($checkToken)) {
            $estudiantes = Estudiantes::query()->where('idapoderado', '=', 0)
                ->where('idSubNivel', '=', $idSubNivel)->get();
            return response()->json(array('estudiantes' => $estudiantes, 'status' => 'success', 'message' => 'Estudiantes encontrados', 'code' => 200), 200);
        } else {
            return response()->json($checkToken, 200);
        }
    }

    public function getEstudiantesApoderado(Request $request)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if (is_null($checkToken)) {
            $estudiantes = Estudiantes::query()->where('idapoderado', '!=', 0)->get();
            return response()->json(array('estudiantes' => $estudiantes, 'status' => 'success', 'message' => 'Estudiantes encontrados', 'code' => 200), 200);
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
            $idSubNivel = (!is_null($json) && isset($params->idSubNivel)) ? $params->idSubNivel : null;
            if (!is_null($name) && !is_null($lastname) && !is_null($correo) && !is_null($password) && !is_null($idSubNivel)) {
                $student = new Estudiantes();
                $student->name = $name;
                $student->lastname = $lastname;
                $student->correo = $correo;
                $student->password = $password;
                $student->idSubNivel = $idSubNivel;
                $student->save();
                return Response()->json(array('estudiante' => $student, 'status' => 'success', 'message' => 'Estudiante creado satisfactoriament', 'code' => 200), 200);
            } else {
                return Response()->json(array('status' => 'error', 'message' => 'Faltan datos', 'code' => 400), 200);
            }
        } else {
            return response()->json($checkToken, 200);
        }
    }


}
