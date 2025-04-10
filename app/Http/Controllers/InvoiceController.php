<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Invoices_attachments;
use App\Models\Invoices_details;
use App\Models\Section;
use App\Notifications\Add_invoice;
use App\Notifications\Add_Invoice_new;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Notification;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices' , compact('invoices'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoice' , compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Invoice::create([
            'invoice_number' =>$request->invoice_number,
            'invoice_date' =>$request->invoice_date,
            'due_date' =>$request->due_date,
            'product' =>$request->product,
            'section_id' =>$request->section_id,
            'amount_collection' =>$request->amount_collection,
            'amount_commission' =>$request->amount_commission,
            'discount' =>$request->discount,
            'value_vat' =>$request->value_vat,
            'rate_vat' =>$request->rate_vat,
            'total' =>$request->total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' =>$request->note,
        ]);
        $invoice_id = Invoice::latest()->first()->id;
        Invoices_details::create([
            'id_invoice' =>$invoice_id,
            'invoice_number' =>$request->invoice_number,
            'product' =>$request->product,
            'section' =>$request->section_id,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' =>$request->note,
            'user' =>(Auth::user()->name),

        ]);
        if($request->hasFile('pic')){
            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new Invoices_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id; 
            $attachments->save();

            $image_name = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('attachments/'.$invoice_number),$image_name);
        }

        $user = user::get();
        $invoice_id = Invoice::latest()->first()->id;
        Notification::send($user , new Add_Invoice_new($invoice_id));
        session()->flash('Add' , 'تم اضافه الفاتوره بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = Invoice::where('id' , $id)->first();
        return view('invoices.Status_update' , compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = Invoice::where('id' , $id)->first();
        $sections = Section::all();
        return view('invoices.edit_invoice' , compact('invoices' , 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoices = Invoice::findorfail($request->invoice_id);
        $invoices->update([
            'invoice_number' =>$request->invoice_number,
            'invoice_date' =>$request->invoice_date,
            'due_date' =>$request->due_date,
            'product' =>$request->product,
            'section_id' =>$request->Section,
            'amount_collection' =>$request->amount_collection,
            'amount_commission' =>$request->amount_commission,
            'discount' =>$request->discount,
            'value_vat' =>$request->value_vat,
            'rate_vat' =>$request->rate_vat,
            'total' =>$request->total,
            'note' =>$request->note,
        ]);

        $Invoices_details = Invoices_details::where('id_invoice' , $request->invoice_id);
        $Invoices_details->update([
            'invoice_number' =>$request->invoice_number,
            'product' =>$request->product,
            'section' =>$request->Section,
            'note' =>$request->note,
        ]);
        session()->flash('edit' , 'تم تعديل الفاتوره بنجاح');
        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::where('id' , $invoice_id);
        $attachments = Invoices_attachments::where('invoice_id', $invoice_id)->first();
        $id_page = $request->id_page;

        if(!$id_page == 2){

        if (!empty($attachments->invoice_number)) {
            Storage::disk('public_uploads')->deleteDirectory($attachments->invoice_number);
        }

        $invoice->forcedelete();

        session()->flash('delete_invoice');
        return redirect('invoices');

        }else{
        $invoice->delete();
        session()->flash('archive_invoice');
        return redirect('archive_invoices');
        }
    }
    public function getproducts($id)
    {
        $states = DB::table('products')->where('section_id' , $id)->pluck('product_name' , 'id');
        return json_encode($states);
    }

    public function status_update(Request $request , $id) 
    {
        $invoices = invoice::findOrFail($id);

        if ($request->status === 'مدفوعة') {

            $invoices->update([
                'value_status' => 1,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);

            invoices_Details::create([
                'id_invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'status' => $request->status,
                'value_status' => 1,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        }
        else {
            $invoices->update([
                'value_status' => 3,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);
            invoices_Details::create([
                'id_invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'status' => $request->status,
                'value_status' => 3,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');

    }
    public function paid_invoices(){
        $invoices = Invoice::where('value_status' , 1)->get();
        return view('invoices.paid_invoices' , compact('invoices'));
    }

    public function unpaid_invoices(){
        $invoices = Invoice::where('value_status' , 2)->get();
        return view('invoices.unpaid_invoices' , compact('invoices'));
    }

    public function partly_paid_invoices(){
        $invoices = Invoice::where('value_status' , 3)->get();
        return view('invoices.partly_paid_invoices' , compact('invoices'));
    }
    public function print_invoice($id){
        $invoices = Invoice::where('id' , $id)->first();
        return view('invoices.print_invoice' , compact('invoices')); 
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // إعداد العناوين
        $sheet->setCellValue('A1', 'م ');
        $sheet->setCellValue('B1', 'رقم الفاتورة');
        $sheet->setCellValue('C1', 'تاريخ الفاتوره');
        $sheet->setCellValue('D1', 'تاريخ الاستحقاق');
        $sheet->setCellValue('E1', 'المنتج');
        $sheet->setCellValue('F1', 'القسم');
        $sheet->setCellValue('G1', 'الخصم');
        $sheet->setCellValue('H1', 'نسبه الضريبه');
        $sheet->setCellValue('I1', 'قسمه الضريبه');
        $sheet->setCellValue('J1', 'الاجمالي');
        $sheet->setCellValue('K1', 'الحاله');
        $sheet->setCellValue('L1', 'ملاحظات');
        // جلب البيانات وإضافتها
        $invoices = Invoice::all(); // افترض أن لديك نموذج Invoice
        $row = 2;
        $i = 1;
        foreach ($invoices as $invoice) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $invoice->invoice_number);
            $sheet->setCellValue('C' . $row, $invoice->invoice_date);
            $sheet->setCellValue('D' . $row, $invoice->due_date);
            $sheet->setCellValue('E' . $row, $invoice->product);
            $sheet->setCellValue('F' . $row, $invoice->section->section_name);
            $sheet->setCellValue('G' . $row, $invoice->discount);
            $sheet->setCellValue('H' . $row, $invoice->rate_vat);
            $sheet->setCellValue('I' . $row, $invoice->value_vat);
            $sheet->setCellValue('J' . $row, $invoice->total);
            $sheet->setCellValue('K' . $row, $invoice->status);
            $sheet->setCellValue('L' . $row, $invoice->note);
            $row++;
            $i++;
        }

        // إنشاء الملف وحفظه
        $fileName = 'invoices.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);

        // تنزيل الملف
        return response()->download($fileName)->deleteFileAfterSend();
    }
    public function MarkAsRead(){
        $unreadnotification = Auth::user()->unreadNotifications;
        if($unreadnotification){
            $unreadnotification->MarkAsRead();
        return back();
        }
    }

}

