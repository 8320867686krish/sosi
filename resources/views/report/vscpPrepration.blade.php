<style>
    table {
        border: 1px solid #000;
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    table td, th {
    border: 1px solid black;
    padding: 4px;
    text-align: left;
    font-size: 12px;
}
.next {
    page-break-before: always;

}


</style>
<div class="section-1-1 next">
    <table>
        <thead>
            <tr>
                <th>S.N</th>
                <th style="width:5%">HazMat</th>
                <th>Location</th>
                <th>Equipment</th>
                <th>Component</th>
                <th style="width:4%">Document Analysis</th>
                <th>Remarks</th>
            </tr>

        </thead>
        <tbody>

            @foreach($checks as $check)

            @php $hazmatsCount = count($check->check_hazmats); @endphp
            @if ($hazmatsCount == 0)
            <tr>
                <td>{{$check['name']}}</td>
                <td>&nbsp;</td>
                <td>{{ $check->location }} @if($check->sub_location){{ ',' . $check->sub_location }}
                    @endif</td>
                <td>{{ $check->equipment }}</td>
                <td> {{ $check->component }}</td>
                <td>{{ $check->type }}</td>
                <td>&nbsp;</td>

            </tr>
            @endif
            @foreach ($check->check_hazmats as $index => $hazmat)

            <tr>

                <td>{{$check['name']}}</td>
                <td><span style="background-color:{{$hazmat->hazmat->color}};display:inline-block;width:10px;height:10px;"></span>{{ $hazmat->hazmat->short_name }}</td>
                <td>{{ $check->location }} @if($check->sub_location){{ ',' . $check->sub_location }}
                    @endif</td>
                <td>{{ $check->equipment }}</td>
                <td> {{ $check->component }}</td>

                <td>{{ $check->type }}</td>
                <td>{{ $hazmat->remarks }}</td>

            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>