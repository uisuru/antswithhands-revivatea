<div class="box box-primary">
                <table class="table table-hover" id="table-fields">
                    <tbody>
                    <tr>
                        <th style="width: 200px">Date</th>
                        <th>Description</th>
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Total</th>
                    </tr>
                    @php $total = 0 @endphp
                    @foreach($records as $record)
                        @php $total = (($total + $record->credit) - $record->debit) @endphp

                        <tr>
                            <td>{{ $record->created_at }}</td>
                            <td>{{ $record->transaction_description }}</td>
                            <td>{{ $record->credit }}</td>
                            <td>{{ $record->debit }}</td>
                            <td>{{ $total }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

    </div>

</div>