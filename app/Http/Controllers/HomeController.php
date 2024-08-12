<?php

namespace App\Http\Controllers;

use App\Models\Chain;
use App\Models\Dataform;
use App\Models\Group;
use App\Models\Line;
use App\Models\Product;
use App\Models\Rooms;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;
use Carbon\Carbon;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();





        // Verificar el rol del usuario
        if ($user->hasRole('analista de marketing')) {
            $salas = DB::connection('odbc')->select(
                "SELECT \"CardCode\", \"CardName\", \"AliasName\"
                FROM  \"BD_MINOIL_PROD\".\"OCRD\" WHERE \"frozenFor\" <> 'Y' 
            AND \"Territory\" = 2 ",
            );


            $productos = DB::connection('odbc')->select(
                "SELECT \"ItemCode\", \"ItemName\"
                FROM BD_MINOIL_PROD.OITM ",

            );

            $datosInsertados = DB::connection('odbc')->select(
                "SELECT * FROM \"MINOILDES\".\"TRADE_StockLotes\" "
            );
            // DB::connection('odbc')->delete("DELETE FROM \"MINOILDES\".\"TRADE_StockLotes\"");

            //dd($datosInsertados);
            // Construir la tabla de pivote
            $pivoteData = [];
            $encabezadosItemCode = [];

            foreach ($datosInsertados as $dato) {
                $cardCode = $dato->CardCode;
                $itemCode = $dato->ItemCode ?? 'N/A'; // Manejar valores nulos
                $stock = $dato->Stock ?? 0; // Manejar valores nulos

                // Almacenar los encabezados de ItemCode
                $encabezadosItemCode[] = $itemCode;

                // Agregar datos a la tabla de pivote
                $pivoteData[$cardCode][$itemCode] = isset($pivoteData[$cardCode][$itemCode])
                    ? $pivoteData[$cardCode][$itemCode] + $stock
                    : $stock;
            }

            // Obtener encabezados únicos de ItemCode
            $encabezadosItemCode = array_values(array_unique($encabezadosItemCode));

            // Preparar datos para la vista
            $tablaPivote = [];
            foreach ($pivoteData as $cardCode => $stocks) {
                $fila = ['CardCode' => $cardCode] + array_fill_keys($encabezadosItemCode, 0);
                foreach ($stocks as $itemCode => $stock) {
                    $fila[$itemCode] += $stock; // Modificado para acumular correctamente los valores de Stock
                }
                $tablaPivote[] = $fila;
            }

            // dd($datosInsertados);
            // Enviar datos a la vista
            return view('analyst.home', ['encabezados' => $encabezadosItemCode, 'tablaPivote' => $tablaPivote]);
        }

        // Realizar consultas y operaciones para otros roles aquí
        $datosInsertados = DB::connection('odbc')->select(
            "SELECT * FROM \"MINOILDES\".\"TRADE_StockLotes\" "
        );
        // DB::connection('odbc')->delete("DELETE FROM \"MINOILDES\".\"TRADE_StockLotes\"");
        //dd($datosInsertados);
        $rooms = collect([]);

        $groups = DB::connection('odbc')->select("SELECT \"ItmsGrpCod\", \"ItmsGrpNam\"
            FROM BD_MINOIL_PROD.OITB 
            WHERE \"ItmsGrpCod\" BETWEEN 101 AND 106");

        $lines = collect([]);
        $chains = DB::connection('odbc')->select("SELECT DISTINCT \"GlblLocNum\"
            FROM BD_MINOIL_PROD.OCRD
            WHERE \"frozenFor\" <> 'Y'
            AND \"CardType\" = 'C'
            AND IFNULL(\"GlblLocNum\", '') <> ''
            AND \"Territory\" = $user->city_id");

        $productos = DB::connection('odbc')->select(
            "SELECT   \"ItemCode\",\"ItemName\",\"CodeBars\"
            FROM BD_MINOIL_PROD.oitm
            WHERE \"frozenFor\" <> 'Y'
          "
        );
        $censo = DB::connection('odbc')->select(
            "SELECT  *
            FROM \"MINOILDES\".\"TRADE_TipoCenso\" WHERE \"Id\" > 1 
          "
        );
        return view('home', compact('chains', 'rooms', 'groups', 'lines', 'productos', 'censo'));
    }

    public function getRooms($valor)
    {
        $user = Auth::user();
        $valorReal = strval($valor);

        $rooms = DB::connection('odbc')->select("SELECT \"CardCode\", \"CardName\", CAST(\"AliasName\" AS VARCHAR(30)) as \"AliasName\"
        FROM  \"BD_MINOIL_PROD\".\"OCRD\" 
            WHERE \"frozenFor\" <> 'Y'
            AND \"GlblLocNum\" = '$valorReal'
            AND \"Territory\" = $user->city_id 
            ");
        return response()->json(['rooms' => $rooms, 'valor' => $valorReal]);
    }

    public function getLineasByGroup($groupId)
    {
        $lineas = DB::connection('odbc')->select("SELECT T0.\"U_codlinea\", T0.\"U_linea\"
        FROM \"BD_MINOIL_PROD\".\"@LINEA\" T0
        WHERE T0.\"U_codgrupo\" = $groupId
    ");
        return response()->json(['lineas' => $lineas, 'valor' => $groupId]);
    }
    public function getProductosByLinea($lineaId)
    {

        // Convertir los datos a UTF-8 si es necesario
        $productos = DB::connection('odbc')->select(
            "SELECT \"ItemCode\",\"CodeBars\"
            FROM BD_MINOIL_PROD.OITM
            WHERE \"frozenFor\" <> 'Y'
          "
        );
        // Convertir los datos a UTF-8 si es necesario


        return response()->json(['productos' => $productos, 'valor' => $lineaId]);
    }
    public function saveData(Request $request)
    {

        //dd($request->input('fecha_vencimiento'));
        try {
            \Log::info('Request Data:', $request->all());

            $cardCode = $request->input('sala');
            $itemCode = $request->input('producto');

            $lote = 'null';
            $accion = '1';
            $fv = $request->input('fecha_vencimiento');
            $checkCotizaciones = $request->input('checkCotizaciones');
            $censo = ($checkCotizaciones !== "undefined" && $checkCotizaciones !== null) ? $checkCotizaciones : '1';
            $created_at = now()->toDateString();
            // $created_at = '2024-02-20';

            $stock = $request->input('cantidad');

            $currentMonth = date('m');
            $currentMonthFormatted = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
            $currentYear = date('Y');

            // Validar la fecha FV
            $fvDate = Carbon::parse($fv);
            $startDate = Carbon::now()->subYear(); // Un año antes
            $endDate = Carbon::now()->addYears(3); // Tres años después

            if ($fvDate->lt($startDate) || $fvDate->gt($endDate)) {
                return response()->json(['message' => 'La fecha de vencimiento debe estar dentro del rango año actual  y 3 años después de la fecha actual.'], 400);
            }
            $existingRecord = DB::connection('odbc')->select(
                "SELECT * FROM \"MINOILDES\".\"TRADE_StockLotes\"
            WHERE \"CardCode\" = '$cardCode' AND \"ItemCode\" = '$itemCode'  AND \"IdCenso\" = '$censo'  AND \"FV\" = ' $fv'  
            AND TO_CHAR(\"CreateDate\", 'YYYY-MM') = '$currentYear-$currentMonthFormatted'"
            );
            if (!empty($existingRecord)) {
                // Si existe, actualizar el stock
                DB::connection('odbc')->update(
                    "UPDATE \"MINOILDES\".\"TRADE_StockLotes\"
                    SET \"Stock\" = '$stock'
                    WHERE \"CardCode\" = '$cardCode' AND \"ItemCode\" = '$itemCode'  AND \"IdCenso\" = '$censo'  AND \"FV\" = ' $fv'"
                );
                return response()->json(['message' => 'Datos actualizados']);
            } else {
                // Si no existe, insertar un nuevo registro
                DB::connection('odbc')->insert(
                    "INSERT INTO \"MINOILDES\".\"TRADE_StockLotes\"
                    (\"CardCode\", \"ItemCode\", \"Lote\", \"FV\", \"Stock\", \"CreateDate\",\"IdCenso\") 
                    VALUES (?, ?, ?, TO_DATE(?, 'YYYY-MM-DD'), ?, TO_DATE(?, 'YYYY-MM-DD'),  ?)",
                    [$cardCode, $itemCode, $lote, $fv, $stock, $created_at, $censo]
                );
                return response()->json(['message' => 'Datos guardados']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e]);
        }
        //dd($request->all());
        // Return a response indicating success or failure
    }
}
