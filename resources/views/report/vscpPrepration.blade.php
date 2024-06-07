<div class="section-1-1">
    <table>
        <thead>
            <tr>
                <th>S.N</th>
                <th>HazMat</th>
                <th>Location</th>
                <th>Equipment</th>
                <th>Component</th>
                <th>Document Analysis</th>
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
                <td><span style="background-color:{{$hazmat->hazmat->color}};display:inline-block;height:10%"></span>{{ $hazmat->hazmat->short_name }}</td>
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