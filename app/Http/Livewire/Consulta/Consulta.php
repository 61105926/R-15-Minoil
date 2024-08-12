<?php

namespace App\Http\Livewire\Consulta;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Consulta extends Component
{
    public $sede;
    public $categoria;
    public $month;
    public $year;


    public $datosInsertados;
    public $pivotData = [];
    public $encabezadosSala = [];
    public $fechasFV = [];


    public $productoNombres = []; // Agrega esta línea

    public $textoPersonalizado = [];
    public $selectedAcciones = [];
    public $selectedAporte = [];
    public $showText = [];

    public function render()
    {

        $acciones = DB::connection('odbc')->select(
            "SELECT * FROM \"MINOILDES\".\"TRADE_Acciones\""
        );
        $aportes = DB::connection('odbc')->select(
            "SELECT * FROM \"MINOILDES\".\"TRADE_TipoAporte\""
        );
        $sucursal = DB::connection('odbc')->select(
            "SELECT * FROM \"BD_MINOIL_PROD\".\"OUBR\" WHERE  \"Code\" >'0'  "
        );
        $groups = DB::connection('odbc')->select("SELECT DISTINCT \"U_JefeMarca\"
        FROM \"BD_MINOIL_PROD\".\"@LINEA\" 
       ");
        // Enviar datos a la vista  
        //  dd($result);
        return view('livewire.consulta.consulta', compact('sucursal', 'groups', 'acciones', 'aportes'));
    }
    public function mostrarDatos()
    {
        dd('hola');
        $this->validate([
            'categoria' => 'required',
            'month' => 'required',
            'year' => 'required',
            'sede' => 'required',
        ]);


        $rooms = DB::connection('odbc')->select("SELECT \"CardCode\", \"CardName\",CAST(\"AliasName\" AS VARCHAR(30)) as \"AliasName\"
            FROM  \"BD_MINOIL_PROD\".\"OCRD\" 
            WHERE \"frozenFor\" <> 'Y'
            AND \"Territory\" = '$this->sede'
            ");

        // Tu lógica para recuperar datos aquí
        // dd($this->categoria);
        // Establecer un mensaje de éxito
        $this->datosInsertados = DB::connection('odbc')->select(
            "SELECT  t0.\"CardCode\",t0.\"ItemCode\",t0.\"FV\",t0.\"Stock\",t1.\"ItemName\",t3.\"CardName\",CAST(t3.\"AliasName\" AS VARCHAR(30)) AS \"AliasName\"

                FROM \"MINOILDES\".\"TRADE_StockLotes\" t0
                INNER JOIN \"BD_MINOIL_PROD\".\"OITM\" t1 ON t0.\"ItemCode\" = t1.\"ItemCode\"
                INNER JOIN \"BD_MINOIL_PROD\".\"@LINEA\" t2 on  t2.\"U_codlinea\"=t1.\"U_subgrupo\"
                INNER JOIN \"BD_MINOIL_PROD\".\"OCRD\" t3 on  t3.\"CardCode\"=t0.\"CardCode\"
                WHERE t2.\"U_JefeMarca\" = '$this->categoria'
                    AND YEAR(t0.\"CreateDate\") = '$this->year'
                    AND MONTH(t0.\"CreateDate\") = '$this->month'
                    AND t0.\"CardCode\" IN (
                        SELECT \"CardCode\"
                        FROM \"BD_MINOIL_PROD\".\"OCRD\"
                        WHERE \"frozenFor\" <> 'Y'
                            AND \"CardType\" = 'C'
                            AND \"Territory\" = '$this->sede'
                    )",

        );


        $this->pivotData = [];
        $this->encabezadosSala = [];
        $this->fechasFV = [];

        foreach ($this->datosInsertados as $dato) {
            $producto = $dato->ItemCode;
            $sala = $dato->CardCode;
            $AliasName = $dato->AliasName;

            $quantity = $dato->Stock;
            $fecha = $dato->FV;
            $salaNombre = $dato->CardName;
            $productoNombre = $dato->ItemName;

            if (!array_key_exists($sala, $this->encabezadosSala)) {
                $this->encabezadosSala[$sala] = [
                    'codigo' => $sala,
                    'nombre' => $salaNombre,
                    'AliasName' => $AliasName,

                ];
            }

            $this->pivotData[$producto][$fecha][$sala] = $quantity;
            if (!isset($this->productoNombres[$producto])) {
                $this->productoNombres[$producto] = $productoNombre;
            }

            if (!in_array($fecha, $this->fechasFV)) {
                $this->fechasFV[] = $fecha;
            }
        }
    }
    public function enviarTexto($producto, $fecha)
    {
        // dd($this->textoPersonalizado[$producto][$fecha]);

        DB::connection('odbc')->update(
            "UPDATE \"MINOILDES\".\"TRADE_StockLotes\"
            SET \"IdAccion\" = ?,
            \"Comentario\" = 'nuevo'
            WHERE \"ItemCode\" = ? 
                AND \"FV\" = TO_DATE(?, 'YYYY-MM-DD')",
            [0, $producto, $fecha]
        );

        $this->dispatch('datos-actualizados',  ['success' => true]);
    }
    public function actualizarDatos($producto, $fecha)
    {


        try {
            // Lógica de actualización con $this->selectedAccion
            // Puedes acceder a $this->selectedAccion para obtener el valor seleccionado.
            // Realiza las operaciones de actualización que necesites aquí.
            $selectedAccionId = $this->selectedAcciones[$producto][$fecha];
            DB::connection('odbc')->update(
                "UPDATE \"MINOILDES\".\"TRADE_StockLotes\"
                SET \"IdAccion\" = ?
                WHERE \"ItemCode\" = ? 
                    AND \"FV\" = TO_DATE(?, 'YYYY-MM-DD')",
                [$selectedAccionId, $producto, $fecha]
            );
            // Emitir un evento si la actualización fue exitosa
            $this->dispatch('datos-actualizados',  ['success' => true]);
        } catch (\Exception $e) {
            // Emitir un evento si la actualización falló
        }
    }
    public function actualizarDatosAporte($producto, $fecha)
    {


        try {
            $selectedAccionIds = $this->selectedAporte[$producto][$fecha];
            DB::connection('odbc')->update(
                "UPDATE \"MINOILDES\".\"TRADE_StockLotes\"
                SET \"IdPresupuesto\" = ?
                WHERE \"ItemCode\" = ? 
                    AND \"FV\" = TO_DATE(?, 'YYYY-MM-DD')",
                [$selectedAccionIds, $producto, $fecha]
            );
            // Emitir un evento si la actualización fue exitosa
            $this->dispatch('datos-actualizados',  ['success' => true]);
        } catch (\Exception $e) {
            // Emitir un evento si la actualización falló
        }
    }
}
