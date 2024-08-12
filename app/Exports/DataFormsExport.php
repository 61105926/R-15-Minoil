<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataFormsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
   
    public function collection()
    {
        // Obtén los datos desde la base de datos
        $data = DB::table('dataforms')->select('product', 'room', 'quantity', 'expiration_date')
            ->orderBy('product')
            ->orderBy('expiration_date')
            ->get();
    
        $salas = $data->pluck('room')->unique();
    
        $pivotData = [];
    
        foreach ($data as $row) {
            $pivotData[$row->product][$row->room][] = [
                'quantity' => $row->quantity,
                'expiration_date' => $row->expiration_date,
            ];
        }
    
        // Prepara los datos en el formato correcto para Excel
        $excelData = [['Productos / Salas']];
    
        foreach ($salas as $sala) {
            $excelData[0][] = $sala;
        }
    
        foreach ($pivotData as $producto => $salaData) {
            foreach ($salaData as $sala => $productData) {
                $row = [$producto];
                foreach ($salas as $salaName) {
                    $quantity = '';
                    $expirationDate = '';
                    foreach ($productData as $data) {
                        if ($data['expiration_date'] == $salaName) {
                            $quantity = $data['quantity'];
                            $expirationDate = $data['expiration_date'];
                            break;
                        }
                    }
                    $row[] = $quantity;
                }
                $row[] = $expirationDate;
                $excelData[] = $row;
            }
        }
    
        return collect($excelData);
    }
    


}
