<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;

    public $table = "invoice_products";

    public $timestamps = false;

    public $fillable = ['invoice_id', 'product_name', 'product_price', 'product_discount'];

    public function invoice()
    {
        return $this->belongsTo(\App\Models\invoice::class);
    }
}
