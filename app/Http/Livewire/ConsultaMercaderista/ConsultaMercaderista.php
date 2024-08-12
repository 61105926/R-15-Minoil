<?php

namespace App\Http\Livewire\ConsultaMercaderista;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Livewire\Attributes\On;


class ConsultaMercaderista extends Component
{
    public $chains;
    public $room_id;
    public $chain_id;
    public $rooms = [];
    public $user;
    public $datosInsertados;


    public function mount()
    {
        $person = Auth::user();
        $user = $person->city_id;
        // dd($user);
    }
    public function cadena()
    {

        $valorReal = strval($this->chain_id);
        $user =  Auth::user();
        $this->rooms = DB::connection('odbc')->select("SELECT \"CardCode\", \"CardName\", CAST(\"AliasName\" AS VARCHAR(30)) as \"AliasName\"
        FROM  \"BD_MINOIL_PROD\".\"OCRD\" 
            WHERE \"frozenFor\" <> 'Y'
            AND \"GlblLocNum\" = '$valorReal'
            AND \"Territory\" = $user->city_id ");

        // dd($this->rooms);
    }

    public function mostrarDatos()
    {
        
        $currentMonth = date('n');
        $currentYear = date('Y');
        // dd($currentMonth,$currentYear);
        $this->datosInsertados = DB::connection('odbc')->select(
            "SELECT  t0.\"CardCode\",t0.\"ItemCode\",t0.\"FV\",t0.\"Stock\",t0.\"IdCenso\",t0.\"CreateDate\",t1.\"ItemName\",t3.\"CardName\",t3.\"AliasName\"
            FROM \"MINOILDES\".\"TRADE_StockLotes\" t0
            INNER JOIN \"BD_MINOIL_PROD\".\"OITM\" t1 ON t0.\"ItemCode\" = t1.\"ItemCode\"
            INNER JOIN \"BD_MINOIL_PROD\".\"@LINEA\" t2 on  t2.\"U_codlinea\"=t1.\"U_subgrupo\"
            INNER JOIN \"BD_MINOIL_PROD\".\"OCRD\" t3 on  t3.\"CardCode\"=t0.\"CardCode\"
            WHERE YEAR(t0.\"CreateDate\") = '$currentYear' 
            AND MONTH(t0.\"CreateDate\") = '$currentMonth'
            AND t0.\"CardCode\" ='$this->room_id'
            ORDER BY t0.\"CreateDate\" DESC"
        );
        // dd($this->datosInsertados);
    }

    public function confirmDelete($CardCode, $ItemCode, $FV, $Stock, $IdCenso, $CreateDate)
    {
        $this->dispatch('showConfirmation', [
            'CardCode' => $CardCode,
            'ItemCode' => $ItemCode,
            'FV' => $FV,
            'Stock' => $Stock,
            'IdCenso' => $IdCenso,
            'CreateDate' => $CreateDate
        ]);
    }
    #[On('deleteConfirmed')]
    public function deleteConfirmed($parameter)
    {
        $CardCode = $parameter['CardCode'];
        $ItemCode = $parameter['ItemCode'];
        $IdCenso = $parameter['IdCenso'];
        $CreateDate = $parameter['CreateDate'];
        $Stock = $parameter['Stock'];
        $FV = $parameter['FV'];
        DB::connection('odbc')->delete(
            "DELETE FROM \"MINOILDES\".\"TRADE_StockLotes\"
            WHERE \"CardCode\" = '$CardCode' 
            AND \"ItemCode\" = '$ItemCode' 
            AND \"IdCenso\" = '$IdCenso'  
         AND \"CreateDate\" = '$CreateDate'  
         AND \"Stock\" = '$Stock'  
         AND \"FV\" = '$FV'"
        );
        $this->mostrarDatos();
    }
    public function render()
    {
        $user = Auth::user();

        $this->chains = DB::connection('odbc')->select("SELECT DISTINCT \"GlblLocNum\"
        FROM BD_MINOIL_PROD.OCRD
        WHERE \"frozenFor\" <> 'Y'
        AND \"CardType\" = 'C'
        AND IFNULL(\"GlblLocNum\", '') <> ''
        AND \"Territory\" = $user->city_id");



        return view('livewire.consulta-mercaderista.consulta-mercaderista', ['chains' => $this->chains, 'rooms' => $this->rooms]);
    }
}
