<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SalesController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        $input = $request->all();
        if(isset($input['dateTo']) && isset($input['dateFrom']) ){
            $dateCondition = "AND data >= '".$input['dateFrom']."' AND data <= '".$input['dateTo']."'  ";
        }else{
            $dateCondition = '';
        }
        $data = DB::select("
            SELECT 
                grupy_produktow.nazwa, 
                zamowienia.data,
                SUM(produkty.cena_netto*ilosc) AS cena_netto ,
                SUM((produkty.cena_netto*ilosc) * (1+(produkty.vat/100)) ) AS cena_brutto 
            FROM 
                `zamowienia` 
                INNER JOIN produkty ON zamowienia.id_produkt = produkty.id 
                INNER JOIN grupy_produktow ON produkty.id_grupa = grupy_produktow.id
            WHERE 
                1 = 1
                $dateCondition
            GROUP BY 
                grupy_produktow.nazwa, 
                zamowienia.data
            ORDER BY data ASC, nazwa DESC;");
        $chartData = array();
        foreach($data AS $row){
            if(empty($chartData[$row->nazwa]['nettoVal']))  $chartData[$row->nazwa]['nettoVal'] = 0;
            if(empty($chartData[$row->nazwa]['bruttoVal'])) $chartData[$row->nazwa]['bruttoVal'] = 0;
            $chartData[$row->nazwa]['nettoVal']   += $row->cena_netto;
            $chartData[$row->nazwa]['bruttoVal']  += $row->cena_brutto;
        }
        return view('sales/report',compact('data'))->with('request',$request)->with('chartData',$chartData);
    }
    public function chart()
    {
        return view('sales/chart');
    }
}
