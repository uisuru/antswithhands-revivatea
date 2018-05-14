<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Bin Card</h3>
    </div>
    <div class="box-body">

        <form action="{{route('post-bincard')}}" method="post" id="scaffold" pjax-container="">
            <!-- /.box-header -->
            <div class="box-body">

                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="inputTableName" class="col-sm-2 control-label">Product</label>

                        <div class="col-sm-6">
                            <select style="width: 200px" name="product_name" tabindex="-1"
                                    class="select2-hidden-accessible" aria-hidden="true">
                                <option value="">Select</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputTableName" class="col-sm-2 control-label">Warehouse</label>

                        <div class="col-sm-6">
                            <select style="width: 200px" name="warehouse" tabindex="-1"
                                    class="select2-hidden-accessible" aria-hidden="true">
                                <option value="">Select</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name}}</option>
                                @endforeach
                            </select>
                        </div>
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


<script>

    $(function () {

        $('select').select2();

    });

</script>