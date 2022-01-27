<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public $table = "invoices";

    public $fillable = ['customer_name', 'customer_email', 'qty', 'sub_total', 'total_discount', 'final_total'];

    public function invoiceproduct()
    {
        return $this->hasMany(\App\Models\invoiceproduct::class, 'invoice_id', 'id');
    }
}
