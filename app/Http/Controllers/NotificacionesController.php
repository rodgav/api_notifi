<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\Models\Estudiantes;
use App\Models\Notificaciones;
use Illuminate\Http\Request;

class NotificacionesController extends Controller
{
    public function notificacionesAll(Request $request)
    {
        //recibir datos
        $json = $request->input('json', null);
        $params = json_decode($json);
        $titulo = (!is_null($json) && isset($params->titulo)) ? $params->titulo : null;
        $mensaje = (!is_null($json) && isset($params->mensaje)) ? $params->mensaje : null;
        $date_limit = (!is_null($json) && isset($params->date_limit)) ? $params->date_limit : null;
        if (!is_null($titulo) && !is_null($mensaje) && !is_null($date_limit)) {
            $tokensStudents = Estudiantes::query()
                ->join('apoderado', 'apoderado.id', '=', 'estudiantes.idapoderado')
                //->join('tokensFCM', 'tokensFCM.idUser', '=', 'apoderado.id')
                ->where('estudiantes.idapoderado', '=', 0)
                //->where('tokensFCM.role', '=', 'student')
                ->select('apoderado.id as idApoderado', 'estudiantes.id as idEstudiante'
                //,'tokensFCM.token'
                )
                ->get();
            $tokensProxies = Estudiantes::query()
                ->join('apoderado', 'apoderado.id', '=', 'estudiantes.idapoderado')
                //->join('tokensFCM', 'tokensFCM.idUser', '=', 'apoderado.id')
                ->where('estudiantes.idapoderado', '!=', 0)
                //->where('tokensFCM.role', '=', 'proxie')
                ->select('apoderado.id as idApoderado', 'estudiantes.id as idEstudiante'
                //,'tokensFCM.token'
                )
                ->get();
            foreach ($tokensStudents as $val) {
                Notificaciones::query()->insert([
                    'idapoderado' => $val->idApoderado,
                    'idEstudiante' => $val->idEstudiante,
                    'titulo' => $titulo,
                    'mensaje' => $mensaje,
                    'date_limit' => $date_limit,
                ]);
            }
            foreach ($tokensProxies as $val) {
                Notificaciones::query()->insert([
                    'idapoderado' => $val->idApoderado,
                    'idEstudiante' => $val->idEstudiante,
                    'titulo' => $titulo,
                    'mensaje' => $mensaje,
                    'date_limit' => $date_limit,
                ]);
            }
            return response()->json(array('notiAllS' => $tokensStudents, 'notiAllA' => $tokensProxies, 'status' => 'success', 'message' => 'Estudiantes encontrados', 'code' => 200), 200);
        } else {
            return Response()->json(array('status' => 'error', 'message' => 'Faltan datos', 'code' => 400), 200);
        }
    }

    public function notificacionesGrades(Request $request, $idSubNivel)
    {
        //recibir datos
        $json = $request->input('json', null);
        $params = json_decode($json);
        $titulo = (!is_null($json) && isset($params->titulo)) ? $params->titulo : null;
        $mensaje = (!is_null($json) && isset($params->mensaje)) ? $params->mensaje : null;
        $date_limit = (!is_null($json) && isset($params->date_limit)) ? $params->date_limit : null;
        if (!is_null($titulo) && !is_null($mensaje) && !is_null($date_limit)) {
            $tokensStudents = Estudiantes::query()
                ->join('apoderado', 'apoderado.id', '=', 'estudiantes.idapoderado')
                //->join('tokensFCM', 'tokensFCM.idUser', '=', 'apoderado.id')
                ->where('estudiantes.idapoderado', '=', 0)
                ->where('estudiantes.idSubNivel', '=', $idSubNivel)
                //->where('tokensFCM.role', '=', 'student')
                ->select('apoderado.id as idApoderado', 'estudiantes.id as idEstudiante'
                //,'tokensFCM.token'
                )
                ->get();
            $tokensProxies = Estudiantes::query()
                ->join('apoderado', 'apoderado.id', '=', 'estudiantes.idapoderado')
                //->join('tokensFCM', 'tokensFCM.idUser', '=', 'apoderado.id')
                ->where('estudiantes.idapoderado', '!=', 0)
                ->where('estudiantes.idSubNivel', '=', $idSubNivel)
                //->where('tokensFCM.role', '=', 'proxie')
                ->select('apoderado.id as idApoderado', 'estudiantes.id as idEstudiante'
                //,'tokensFCM.token'
                )
                ->get();
            foreach ($tokensStudents as $val) {
                Notificaciones::query()->insert([
                    'idapoderado' => $val->idApoderado,
                    'idEstudiante' => $val->idEstudiante,
                    'titulo' => $titulo,
                    'mensaje' => $mensaje,
                    'date_limit' => $date_limit,
                ]);
            }
            foreach ($tokensProxies as $val) {
                Notificaciones::query()->insert([
                    'idapoderado' => $val->idApoderado,
                    'idEstudiante' => $val->idEstudiante,
                    'titulo' => $titulo,
                    'mensaje' => $mensaje,
                    'date_limit' => $date_limit,
                ]);
            }
            return response()->json(array('notiGradeS' => $tokensStudents, 'notiGradeA' => $tokensProxies, 'status' => 'success', 'message' => 'Estudiantes encontrados', 'code' => 200), 200);
        } else {
            return Response()->json(array('status' => 'error', 'message' => 'Faltan datos', 'code' => 400), 200);
        }
    }

    public function getNotificaciones(Request $request)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        //recuperamos idNivel
        $idEstudiante = $request->query('idEstudiante');
        $checkToken = $jwtAuth->checkToken($token);
        if (is_null($checkToken)) {
            $notif = Notificaciones::query()
                ->where(array('idEstudiante' => $idEstudiante))
                ->orderBy('created_at','desc')
                ->get();
            return response()->json(array('notificaciones' => $notif, 'status' => 'success', 'message' => 'Notificaciones encontradas', 'code' => 200), 200);
        } else {
            return response()->json($checkToken, 200);
        }
    }
    public function getNotificacionesProximas(Request $request)
    {
        $token = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        //recuperamos idNivel
        $idEstudiante = $request->query('idEstudiante');
        $checkToken = $jwtAuth->checkToken($token);
        if (is_null($checkToken)) {
            $notif = Notificaciones::query()
                ->where(array('idEstudiante' => $idEstudiante))
                ->orderBy('date_limit','asc')
                ->get();
            return response()->json(array('notificaciones' => $notif, 'status' => 'success', 'message' => 'Notificaciones encontradas', 'code' => 200), 200);
        } else {
            return response()->json($checkToken, 200);
        }
    }
}
