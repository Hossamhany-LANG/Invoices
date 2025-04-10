<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Fx3costa\Laravelchartjs\ChartJs;

use App\Models\Invoice;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
//=================احصائية نسبة تنفيذ الحالات======================
      $count_all =invoice::count();
      $count_invoices1 = invoice::where('value_status', 1)->count();
      $count_invoices2 = invoice::where('value_status', 2)->count();
      $count_invoices3 = invoice::where('value_status', 3)->count();

      if($count_invoices2 == 0){
          $nspainvoices2=0;
      }
      else{
          $nspainvoices2 = $count_invoices2/ $count_all*100;
      }
        if($count_invoices1 == 0){
            $nspainvoices1=0;
        }
        else{
            $nspainvoices1 = $count_invoices1/ $count_all*100;
        }
        if($count_invoices3 == 0){
            $nspainvoices3=0;
        }
        else{
            $nspainvoices3 = $count_invoices3/ $count_all*100;
        }
        
        return view('dashboard', compact('nspainvoices2','nspainvoices1', 'nspainvoices3'));

    }
}
