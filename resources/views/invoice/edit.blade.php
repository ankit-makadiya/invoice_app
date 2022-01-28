@extends('adminlte::page')

@section('title', 'Invoice | ' . env("APP_NAME"))

@section('content_header')
<h1>Invoice</h1>
<h6>Edit Invoice</h6>
@stop
@section('css')
<style>
    label.error{
        color: red;
        font-size:13px;
    }
</style>
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>
            <form class="form-horizontal" id="invoice-form">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group row">
                        <label for="customer_name" class="col-sm-2 col-form-label">Customer Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="customer_name" class="form-control" id="customer_name" placeholder="Customer Name" required value="{{ $invoice->customer_name ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="customer_email" class="col-sm-2 col-form-label">Customer Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="customer_email" class="form-control" id="customer_email" placeholder="Customer Email" value="{{ $invoice->customer_email ?? '' }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" id="addMore" class="btn btn-success btn-sm float-right">Add More
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="customer_product" class="col-sm-2 col-form-label">Products</label>
                        <div class="col-sm-10">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Discount (%)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="addRow" class="addRow">
                                    @foreach($invoice->invoiceproduct()->get() as $key => $product)
                                        <input type="hidden" name="addMore[0][id]" value="{{ $product->id ?? '' }}">
                                    <tr>
                                        <td><input name="addMore[0][product_name]" type="text" class="form-control"
                                                placeholder="Product Name" required value="{{ $product->product_name ?? '' }}"></td>
                                        <td><input name="addMore[0][product_price]" type="number" step="0.01"
                                                class="form-control product_price" placeholder="Product Price" required min="0" value="{{ $product->product_price ?? '' }}"></td>
                                        <td><input name="addMore[0][product_discount]" type="number" step="0.01"
                                                class="form-control product_discount" placeholder="Product Discount" required min="0" max="99.99" value="{{ $product->product_discount ?? '' }}"></td>
                                        <td>@if ($key != 0) <button class="btn btn-danger remove-input-field">Remove</button> @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Total Items</label>
                        <div class="col-sm-10" id="total_item">{{ $invoice->qty ?? '' }}</div>
                        <input type="hidden" name="total_item" id="input_total_item" value="{{ $invoice->qty ?? '' }}">
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Total Amount</label>
                        <div class="col-sm-10" id="total_amount">{{ $invoice->sub_total ?? '' }}</div>
                        <input type="hidden" name="total_amount" id="input_total_amount" value="{{ $invoice->sub_total ?? '' }}">
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Total Discount Amount</label>
                        <div class="col-sm-10" id="total_discount_amount">{{ $invoice->total_discount ?? '' }}</div>
                        <input type="hidden" name="total_discount_amount" id="input_total_discount_amount" value="{{ $invoice->total_discount ?? '' }}">
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Total Bill</label>
                        <div class="col-sm-10" id="total_bill">{{ $invoice->final_total ?? '' }}</div>
                        <input type="hidden" name="total_bill" id="input_total_bill" value="{{ $invoice->final_total ?? '' }}">
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-info float-right">Cancel</a>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>
@section('plugins.JqueryValidate', true)
@section('plugins.Sweetalert2', true)
@stop

@section('js')
<script>
var i = 0;
$('#addMore').click(function() {
    ++i;
    $("#addRow").append('<tr><td><input name="addMore[' + i + '][product_name]" type="text" class="form-control" placeholder="Product Name" required></td><td><input name="addMore[' + i + '][product_price]" type="number" step="0.01" class="form-control product_price" placeholder="Product Price" required min="0"></td><td><input name="addMore[' + i + '][product_discount]" type="number" step="0.01" class="form-control product_discount" placeholder="Product Discount" required min="0" max="99.99"></td><td><button class="btn btn-danger remove-input-field">Remove</button></td></tr>');
    $('#total_item').html(i + 1);
    $('#input_total_item').val(i + 1);
});

$(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();
    updatecart();
});

$( "body" ).delegate( ".product_price", "keyup", function() {
    updatecart();
});

$( "body" ).delegate( ".product_discount", "keyup", function() {
    updatecart();
});
function updatecart(){
    var price_class = $(".product_price");
    var discount_class = $(".product_discount");
    var total_price = 0;
    for(var i = 0; i < price_class.length; i++){
        if($(price_class[i]).val() != '' && $(price_class[i]).val() != ''){
            total_price = parseFloat(total_price) + parseFloat($(price_class[i]).val());
        }
    }
    $('#total_amount').html(total_price);
    $('#input_total_amount').val(total_price);

    var discount_price = 0;
    for(var i = 0; i < discount_class.length; i++){
        if($(price_class[i]).val() != '' && $(discount_class[i]).val() != ''){
            discount_price = parseFloat(discount_price) + parseFloat(($(price_class[i]).val()*$(discount_class[i]).val())/100);
        }
    }
    $('#total_discount_amount').html(discount_price.toFixed(2));
    $('#input_total_discount_amount').val(discount_price.toFixed(2));

    var total_bill = $('#input_total_amount').val() - $('#input_total_discount_amount').val();
    $('#total_bill').html(total_bill.toFixed(2));
    $('#input_total_bill').val(total_bill.toFixed(2));
}
$("#invoice-form").validate({
    submitHandler: function(form) {
        $.ajax({
            url: "{{ route('invoices.update', $invoice->id) }}",
            type:'PUT',
            data: $(form).serialize(),
            success: function(data) {
                if($.isEmptyObject(data.error)){
                    Swal.fire({
                        title: 'Success',
                        text: data.success,
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                        }).then((result) => {
                            window.location.href="/invoices";
                        })
                }else{
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: data.error,
                    })
                }
            }
        });
    }
 });

function printErrorMsg (msg) {
    $(".print-error-msg").find("ul").html('');
    $(".print-error-msg").css('display','block');
    $.each( msg, function( key, value ) {
        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
    });
}
</script>
@stop