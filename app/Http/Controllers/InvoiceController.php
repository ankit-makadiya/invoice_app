<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use Validator;

class InvoiceController extends Controller
{
    public function index(){
        $invoices = Invoice::all();
        return view("invoice/index", compact(['invoices']));
    }

    public function create(){
        return view("invoice/new");
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'addMore.*.product_name' => 'required',
            'addMore.*.product_price' => 'required|numeric',
            'addMore.*.product_discount' => 'required|numeric|between:0,99.99',
        ]);
        if ($validator->passes()) {
            $invoice = new Invoice();
	        $invoice->customer_name = $request->customer_name;
	        $invoice->customer_email = $request->customer_email;
            $invoice->qty = $request->total_item;
            $invoice->sub_total = $request->total_amount;
            $invoice->total_discount = $request->total_discount_amount;
            $invoice->final_total = $request->total_bill;
	        $invoice->save();

            foreach($request->addMore as $product){
                $invoiceProduct = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
                $invoiceProduct->product_name = $product['product_name'];
                $invoiceProduct->product_price = $product['product_price'];
                $invoiceProduct->product_discount = $product['product_discount'];
                $invoiceProduct->save();
            }

            return response()->json(['success'=>'Invoice has been created successfully']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function edit(Invoice $invoice){
        return view("invoice/edit", compact('invoice'));
    }

    public function update(Request $request, $id)
    {
    	$validator = Validator::make($request->all(), [
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'addMore.*.product_name' => 'required',
            'addMore.*.product_price' => 'required|numeric',
            'addMore.*.product_discount' => 'required|numeric|between:0,99.99',
        ]);
        if ($validator->passes()) {
            $invoice = Invoice::find($id);
            $invoice->customer_name = $request->customer_name;
	        $invoice->customer_email = $request->customer_email;
            $invoice->qty = $request->total_item;
            $invoice->sub_total = $request->total_amount;
            $invoice->total_discount = $request->total_discount_amount;
            $invoice->final_total = $request->total_bill;
	        $invoice->save();

            foreach($request->addMore as $product){
                if(isset($product['id'])){
                    $invoiceProduct = InvoiceProduct::find($product['id']);
                }else{
                    $invoiceProduct = new InvoiceProduct();
                }
                $invoiceProduct->invoice_id = $invoice->id;
                $invoiceProduct->product_name = $product['product_name'];
                $invoiceProduct->product_price = $product['product_price'];
                $invoiceProduct->product_discount = $product['product_discount'];
                $invoiceProduct->save();
            }

            return response()->json(['success'=>'Invoice has been updated successfully']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function show(Invoice $invoice)
    {
        return view('invoice.show',compact('invoice'));
    } 
}
