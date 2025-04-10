<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\Invoice;

class ClientsReportsController extends Controller
{
    
    public function index(){
        $sections = Section::all();
        return view('reports.clients_report' , compact('sections'));
    }

    public function Search_clients(Request $request){
        
    // في حالة البحث بدون التاريخ
        if ($request->Section && $request->product && $request->start_at =='' && $request->end_at=='') {

        $invoices = invoice::select('*')->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
        $sections = section::all();
        return view('reports.clients_report',compact('sections','invoices'));
        }
    // في حالة البحث بتاريخ
        else {
        $start_at = date($request->start_at);
        $end_at = date($request->end_at);

        $invoices = invoice::whereBetween('invoice_date',[$start_at,$end_at])->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
        $sections = section::all();
        return view('reports.clients_report',compact('sections','invoices'));
        }
    }
}

