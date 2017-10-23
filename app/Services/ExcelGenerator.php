<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExcelGenerator {
    public static function create($data){
        self::createExcel($data);
    }

    private static function createExcel($data)
    {
        Excel::create('Stats', function($excel) use($data) {
            $excel = self::datos_de_archivo($excel);

            $datos = self::crear_datos($data);

            $excel = self::crear_hoja($excel, $datos);

        })->download('xlsx');
    }

    private static function datos_de_archivo($excel)
    {
        // Set the title
        $excel->setTitle('Stats');

        // Chain the setters
        $excel->setCreator(Auth::user()->name)
              ->setCompany(Auth::user()->nam);

        // Call them separately
        $excel->setDescription('General Stats');

        return $excel;
    }

    private static function crear_datos($data)
    {
        $datos_array = array();

        for ($i = 0; $i < sizeof($data['months']); $i++) {
            $datos = [ $data['months'][$i], $data['revenue'][$i], $data['expenses'][$i], $data['utilities'][$i] ];

            array_push($datos_array, $datos);
        }

        $nombres_columnas = ['Mes', 'Ingresos', 'Gastos', 'Utilidades'];

        array_unshift($datos_array, $nombres_columnas);
        return $datos_array;
    }

    private static function crear_hoja($excel, $datos)
    {
        $excel->sheet('Datos', function($sheet) use($datos){
            $sheet->fromArray($datos, null, 'A1', false, false);
        });

        return $excel;
    }
}