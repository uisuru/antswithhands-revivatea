<div class="box box-primary">
    <link href="{{asset('vendor/bootstrp-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet"/>
    <script src="{{asset('vendor/bootstrp-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <div class="box-header with-border">
        <h3 class="box-title">Invoice</h3>
    </div>
    <div class="box-body">

        <form action="{{route('post-invoice')}}" method="post" id="scaffold" pjax-container="">
            <!-- /.box-header -->
            <div class="box-body">

                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="inputTableName" class="col-sm-2 control-label">Invoice Number</label>

                        <div class="col-sm-6">
                            <input type="text" name="invoice_id" class="form-control" id="invoice_id"
                                   placeholder="Invoice Number" required value="{{ old('invoice_id') }}">
                            @if ($errors->has('invoice_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('invoice_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputTableName" class="col-sm-2 control-label">Invoice Date</label>

                        <div class="col-sm-6">
                            <input type="text" name="invoice_date" class="datepicker form-control" id="invoice_date"
                                   value="{{ old('invoice_date') }}" style="width: 100%;"
                                   data-date-format="yyyy-mm-dd" required placeholder="yyyy-mm-dd">
                            @if ($errors->has('invoice_date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('invoice_date') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputTableName" class="col-sm-2 control-label">Customer</label>

                        <div class="col-sm-6">
                            <select class="form-control" name="customer_id" tabindex="-1"
                                    class="select2-hidden-accessible" aria-hidden="true">
                                @foreach($companys as $company)
                                    <option value="{{ $company->id }}">{{ $company->company_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr>

                <h4>Items</h4>
                <table class="table table-hover" id="table-fields">
                    <tbody>
                    <tr>
                        <th style="width: 200px">Product name</th>
                        <th>Retail Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <td>
                            <select style="width: 200px" name="fields[0][product_name]" id="fields[0][product_name]"
                                    tabindex="-1"
                                    class="select2-hidden-accessible" aria-hidden="true" onchange="changePrice(0)">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}"
                                            id="{{$product->retail_price}}">{{ $product->product_name}} </option>
                                @endforeach
                            </select>
                        </td>
                        @foreach ($products as $product)
                            @if ($loop->first)
                                <td><input readonly type="text" class="form-control" value='{{$product->retail_price}}'
                                           placeholder="Retail Price" required name="fields[0][retail_price]"
                                           id="fields[0][retail_price]"></td>
                            @endif
                        @endforeach
                        <td><input type="number" class="form-control" placeholder="Quantity" required
                                   name="fields[0][qty]" id="fields[0][qty]" onchange="price_with_qty(0,this.value)">
                        </td>
                        <td><input readonly type="number" class="form-control" value='0.0' required
                                   name="fields[0][total]" id="fields[0][total]"></td>
                        <td><a class="btn btn-sm btn-danger table-field-remove"><i class="fa fa-trash"></i> remove</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <hr style="margin-top: 0;">
                <table class="table">
                    <tr>
                        <td width="50%">
                            <div class="form-inline margin" style="width: 100%">
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm btn-success" id="add-table-field"><i
                                                class="fa fa-plus"></i>&nbsp;&nbsp;Add field
                                    </button>
                                </div>

                            </div>
                        </td>
                        <td width="10%">
                            Gross Amount
                        </td>
                        <td width="35%">
                            <input disabled type="number" class="form-control" value='0.0' required
                                   name="gross_amount" id="gross_amount">
                        </td>
                        <td width="5%">
                            LKR
                        </td>
                    </tr>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Submit</button>
            </div>

        {{ csrf_field() }}
        <!-- /.box-footer -->
        </form>


    </div>

</div>
<!-- /.box-body -->

<template id="table-field-tpl">
    <tr>
        <td>
            <select style="width: 200px" name="fields[__index__][product_name]" id="fields[__index__][product_name]"
                    tabindex="-1"
                    class="select2-hidden-accessible" aria-hidden="true" onchange="changePrice(__index__)">
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                            id="{{$product->retail_price}}">{{ $product->product_name}}</option>
                @endforeach
            </select>
        </td>
        @foreach ($products as $product)
            @if ($loop->first)
                <td><input readonly type="text" class="form-control" value='{{$product->retail_price}}'
                           placeholder="Retail Price" required name="fields[__index__][retail_price]"
                           id="fields[__index__][retail_price]"></td>
            @endif
        @endforeach
        <td><input type="number" class="form-control" placeholder="Quantity" required name="fields[__index__][qty]"
                   id="fields[__index__][qty]" onchange="price_with_qty(__index__,this.value)"></td>
        <td><input readonly type="number" class="form-control" value='0.0' required name="fields[__index__][total]"
                   id="fields[__index__][total]"></td>
        <td><a class="btn btn-sm btn-danger table-field-remove"><i class="fa fa-trash"></i> remove</a></td>
    </tr>
</template>

<script>

    $(function () {

        $('input[type=checkbox]').iCheck({checkboxClass: 'icheckbox_minimal-blue'});
        $('select').select2();

        $('#add-table-field').click(function (event) {
            $('#table-fields tbody').append($('#table-field-tpl').html().replace(/__index__/g, $('#table-fields tr').length - 1));
            $('select').select2();
            $('input[type=checkbox]').iCheck({checkboxClass: 'icheckbox_minimal-blue'});
        });

        $('#table-fields').on('click', '.table-field-remove', function (event) {
            $(event.target).closest('tr').remove();
        });

        $('#add-model-relation').click(function (event) {
            $('#model-relations tbody').append($('#model-relation-tpl').html().replace(/__index__/g, $('#model-relations tr').length - 1));
            $('select').select2();
            $('input[type=checkbox]').iCheck({checkboxClass: 'icheckbox_minimal-blue'});

            relation_count++;
        });

        $('#model-relations').on('click', '.model-relation-remove', function (event) {
            $(event.target).closest('tr').remove();
        });

        $('#scaffold').on('submit', function (event) {

            //event.preventDefault();

            if ($('#inputTableName').val() == '') {
                $('#inputTableName').closest('.form-group').addClass('has-error');
                $('#table-name-help').removeClass('hide');

                return false;
            }

            return true;
        });
    });

    $('.datepicker').datepicker({
        format: "yyyy-mm-dd"
    });

    function changePrice(index) {
        var x = document.getElementById("fields[" + index + "][product_name]");
        var retail_price = x[x.selectedIndex].id;
        document.getElementById("fields[" + index + "][retail_price]").value = retail_price;
        var qty = document.getElementById("fields[" + index + "][qty]").value;
        total_Create(index, retail_price, qty);
    }

    function price_with_qty(index, qty) {
        //alert("index + input" +index+" "+ qty);
        var retail_price = document.getElementById("fields[" + index + "][retail_price]").value;
        total_Create(index, retail_price, qty);
    }

    function total_Create(index, retail_price, qty) {
        document.getElementById("fields[" + index + "][total]").value = retail_price * qty;
        gross_amount();
    }
    function gross_amount() {
        var value=0;
        for (index = 0; index < $('#table-fields tr').length - 1; index++) {
            value = value + document.getElementById("fields[" + index + "][total]").value*1;
        }
        document.getElementById("gross_amount").value = value;
    }
</script>