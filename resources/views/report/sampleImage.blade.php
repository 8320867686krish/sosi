<div class="container">
    <!-- Section 1.1 -->
    <h2>Appendix-3 Onboard Survey & Lab Analysis Record</h2>
    <div class="section-1-1">
        <table>
            <thead>
                <tr>
                    <th>Sample No</th>
                    <th>Material</th>
                    <th colspan="2">Location</th>
                    <th>Equipment</th>
                    <th>Component</th>
                    <th colspan="2">Sample Quantity</th>
                    <th>Remarks</th>
                    <th>Photograph</th>
                </tr>

            </thead>
            <tbody>
                @foreach($lebResultAll as $value)

              
              
                <tr>
                    <td>{{$value->check->name}}</td>
                    <td>{{ $value->hazmat->short_name }}</td>
                    <td>{{ $value->check->location }} </td>
                    <td>{{ $value->check->sub_location }}</td>
                    <td>{{ $value->check->equipment }}</td>
                    <td> {{ $value->check->component }}</td>
                    <td>{{$value->total}}</td>
                    <td>{{$value->unit}}</td>
                    <td>{{ $value->lab_remarks }}</td>
                    <td><a href="{{$value['check']['checkSingleimage']['image']}}"  target="_blank"><img src="{{$value['check']['checkSingleimage']['image']}}" width="70px" height="70px"/></a></td>
                </tr>
               
                @endforeach
            </tbody>
        </table>
    </div>
</div>
