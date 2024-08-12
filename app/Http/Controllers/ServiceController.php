<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ServiceController extends Controller
{
    //
    public function boleta(Request $request)
    {
        // Obtener el número y la fecha de la solicitud
        $numero = $request->numero;
        $fecha = $request->fecha;
        
        // Llamar al procedimiento almacenado con los parámetros
        $resultados = DB::connection('odbc')->select("CALL \"MINOILDES\".\"RH__API_ConsultaBoleta\"('$numero', $fecha)");
        // Devolver los resultados
       // dd($resultados);
        if(empty($resultados)) {
            // Si no hay resultados, puedes retornar una respuesta indicando que no se encontraron datos
            return response()->json(['message' => 'No se encontraron datos'], 404);
        }
        // Generar el PDF usando el paquete barryvdh/laravel-dompdf
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('boleta.pdf', [
            'resultados' => $resultados,
        ]);

        $pdfFileName = '$resultados[0]->NOMBRE.pdf';

        // Descargar el PDF o mostrarlo en el navegador
        return $pdf->stream($pdfFileName);
  
    }
    public function survey(Request $request){
        $token = $request->query('token');
        try {
            $key = 'your-256-bit-secret'; // La clave secreta debe coincidir con la usada para firmar el token
    
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
    
            return response()->json(['message' => 'Token válido', 'data' => $decoded]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token no válido', 'message' => $e->getMessage()], 400);
        }
        //dd($decoded);
    }
    
}
