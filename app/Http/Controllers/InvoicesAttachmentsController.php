<?php

namespace App\Http\Controllers;

use App\Models\Invoices_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicesAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {               
        // $validate = $request->validate([
        //     'file_name' => 'mimes:pdf, jpeg , jpg , png',
        // ] , [
        //     'file_name.mimes' => 'المرفق يجب أن يكون من صيغه :pdf, jpeg , jpg , png'
        // ]);
            $image = $request->file('file_name');
            $file_name = $image->getClientOriginalName();

            $attachments = new Invoices_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $request->invoice_number;
            $attachments->created_by = Auth::user()->name;
            $attachments->invoice_id = $request->invoice_id; 
            $attachments->save();
            // move pic
            $image_name = $request->file_name->getClientOriginalName();
            $request->file_name->move(public_path('attachments/'.$request->invoice_number),$image_name);
        
        session()->flash('Add' , 'تم اضافه المرفق بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoices_attachments $invoices_attachments)
    {
        //
    }
}
