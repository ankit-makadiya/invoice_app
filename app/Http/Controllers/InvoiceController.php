<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use Validator;

class InvoiceController extends Controller
{
    public function index(){
        return view("invoice/index");
    }

    public function create(){
        return view("invoice/new");
    }

    public function invoicePost(Request $request)
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

            return response()->json(['success'=>'Added new records.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
}
