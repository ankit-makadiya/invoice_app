@extends('adminlte::page')

@section('title', 'Invoice | ' . env("APP_NAME"))

@section('content_header')
<h1>Invoice</h1>
@stop
@section('css')
@stop

@section('content')
<div class="row">
    <div class="col-sm-12">
    <table id="pageTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th>Total Qty</th>
                <th>Sub Total</th>
                <th>Total Discount</th>
                <th>Final Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>sdsds</td>
                <td>52</td>
                <td>1</td>
                <td>sdsds</td>
                <td>52</td>
            </tr>
        </tbody>
    </table>
    </div>
</div>
@section('plugins.Datatables', true)
@stop

@section('js')
<script>
         jQuery(function($) {
        //initiate dataTables plugin
        var myTable = 
        $('#pageTable')
        //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
        .DataTable( {
            bAutoWidth: false,
            "aoColumns": [
                null,
                null,
                null
            ],
            "aaSorting": [],
            //"bProcessing": true,
            //"bServerSide": true,
            //"sAjaxSource": "http://127.0.0.1/table.php"   ,
    
            //,
            //"sScrollY": "200px",
            //"bPaginate": false,
    
            //"sScrollX": "100%",
            //"sScrollXInner": "120%",
            //"bScrollCollapse": true,
            //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
            //you may want to wrap the table inside a "div.dataTables_borderWrap" element
    
            //"iDisplayLength": 50
    
    
                select: {
                    style: 'multi'
                }
            });
        });
    </script>
@stop