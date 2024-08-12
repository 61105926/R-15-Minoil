<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MerchantController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = Auth::user();

        // Realizar consultas y operaciones para otros roles aquí
        $datosInsertados = DB::connection('odbc')->select(
            "SELECT * FROM \"MINOILDES\".\"TRADE_StockLotes\" "
        );
        // DB::connection('odbc')->delete("DELETE FROM \"MINOILDES\".\"TRADE_StockLotes\"");
        // dd($datosInsertados);
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
        return view('merchant.home');
    }
}
