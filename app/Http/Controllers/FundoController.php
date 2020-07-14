<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fundo;
use App\Patrimonio;

class FundoController extends Controller
{
    public function index()
    {
        $fundo = Fundo::all();
        $fundo->load('patrimonios');
        return json_encode($fundo);
    }

    public function byMontarGrafico(Request $request)
    {

        $fundo = Patrimonio::select('patrimonios.fundo_id', 'patrimonios.date','fundos.name')
                ->selectRaw('sum(patrimonios.value) as value')
                ->join('fundos', 'fundos.id', '=', 'patrimonios.fundo_id')
                ->groupBy('patrimonios.fundo_id', 'patrimonios.date','fundos.name','fundos.name')
                ->orderByRaw('fundos.name')
                ->whereBetween('patrimonios.date', [$request->inicial, $request->final])
                ->where('fundo_id', $request->fundo1)->orWhere('fundo_id', $request->fundo2)
                ->orWhere('fundo_id', $request->fundo3)->orWhere('fundo_id', $request->fundo4)
                ->get();

        return json_encode($fundo);
    }


    public function byiniciarGrafico(Request $request)
    {
        $dataHoje = date("Y-m-d");
        $dataSeteDias = date('Y-m-d', strtotime('-7 days'));

        $fundo = Fundo::select('fundos.name')
            ->selectRaw('sum(patrimonios.value) as value')
            ->selectRaw('GROUP_CONCAT(DISTINCT patrimonios.date  ORDER BY patrimonios.date ASC SEPARATOR " , ") as date')
            ->join('patrimonios', 'fundos.id', '=', 'patrimonios.fundo_id')
            ->groupBy('fundos.name')
            ->orderByRaw('fundos.name')
            ->whereBetween('patrimonios.date', ['2020-05-21', '2020-05-27'])
            ->get();
        

        return json_encode($fundo);
    }

}