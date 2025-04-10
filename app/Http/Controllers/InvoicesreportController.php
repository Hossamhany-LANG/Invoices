<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;


class InvoicesreportController extends Controller
{
    public function index(){

        return view('reports.invoices_report');
        
    }

    public function Search_invoices(Request $request){
    $radio = $request->radio;
    $type = $request->type;
 //في حالة البحث بنوع الفاتورة    
    if ($radio == 1) {
 // في حالة عدم تحديد تاريخ
        if ($type != 'كافه الفواتير'&& $request->start_at =='' && $request->end_at =='') {           
            $invoices = invoice::select('*')->where('status','=',$request->type)->get();
            return view('reports.invoices_report',compact('type' , 'invoices'));

        }elseif($type = 'كافه الفواتير' && $request->start_at =='' && $request->end_at ==''){
            $invoices = invoice::all();
            return view('reports.invoices_report',compact('type' , 'invoices'));
        }
        // في حالة تحديد تاريخ استحقاق
        else {       
        $start_at = date($request->start_at);
        $end_at = date($request->end_at);
        
        $invoices = invoice::whereBetween('invoice_date',[$start_at,$end_at])->where('status','=',$request->type)->get();
        return view('reports.invoices_report',compact('type','start_at','end_at' , 'invoices'));       
        }
    } 
// في البحث برقم الفاتورة
    else {      
        $invoices = invoice::select('*')->where('invoice_number','=',$request->invoice_number)->get();
        return view('reports.invoices_report' , compact('invoices'));      
    }
    }

}

