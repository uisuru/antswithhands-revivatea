<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Good Receive Note</h3>
    </div>
    <div class="box-body">

        <form action="{{route('post-grn')}}" method="post" id="scaffold" pjax-container="">
            <!-- /.box-header -->
            <div class="box-body">

                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="inputTableName" class="col-sm-2 control-label">GRN Number</label>

                        <div class="col-sm-6">
                            <input type="text" name="grn_number" class="form-control" id="grn_number"
                                   placeholder="GRN Number" value="{{ old('grn_number') }}">
                            @if ($errors->has('grn_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('grn_number') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputTableName" class="col-sm-2 control-label">Description</label>

                        <div class="col-sm-6">
                            <input type="text" name="description" class="form-control" id="description"
                                   placeholder="Description" value="{{ old('description') }}">
                            @if ($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <hr>

                <h4>Items</h4>
                <table class="table table-hover" id="table-fields">
                    <tbody>
                    <tr>
                        <th style="width: 200px">Product name</th>
                        <th>Qty</th>
                    </tr>
                    <tr>
                        <td>
                            <select style="width: 200px" name="fields[0][product_name]" tabindex="-1"
                                    class="select2-hidden-accessible" aria-hidden="true">
                                <option value="">Select</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" placeholder="Quantity" name="fields[0][qty]"></td>
                        <td><a class="btn btn-sm btn-danger table-field-remove"><i class="fa fa-trash"></i> remove</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <hr style="margin-top: 0;">
                <div class="form-inline margin" style="width: 100%">


                    <div class="form-group">
                        <button type="button" class="btn btn-sm btn-success" id="add-table-field"><i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Add field
                        </button>
                    </div>

                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">submit</button>
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
            <select style="width: 200px" name="fields[__index__][product_name]" tabindex="-1"
                    class="select2-hidden-accessible" aria-hidden="true">
                <option value="">Select</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                @endforeach
            </select>
        </td>

        <td><input type="text" class="form-control" placeholder="comment" name="fields[__index__][qty]"></td>
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

</script>