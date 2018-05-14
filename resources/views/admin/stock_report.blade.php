<div class="box box-primary">
                <table class="table table-hover" id="table-fields">
                    <tbody>
                    <tr>
                        <th>Product Name</th>
                        <th>Actual Stock</th>
                        <th>Re Order Level</th>
                    </tr>
                    @foreach($records as $record)

                        <tr>
                            <td>{{ $record->product_name }}</td>
                            <td>{{ $record->actual_balance }}</td>
                            <td>{{ $record->re_order_level }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

    </div>

</div>