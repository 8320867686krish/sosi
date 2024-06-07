<div class="container">
    <!-- Section 1.1 -->
    <h2>Appendix-3 Onboard Survey & Lab Analysis Record</h2>
    <div class="section-1-1">
        @if(@$sampleImage)
        <h4>Sample Recoreds</h4><br/>
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
            @if(count($sampleImage) > 0)
                @foreach(@$sampleImage as $value)
                @php $hazmatsCount = count($value->labResults); @endphp

                @if($hazmatsCount == 0)
                <tr>
                    <td>{{$value->name}}</td>
                    <td>&nbsp;</td>
                    <td>{{ $value->location }} </td>
                    <td>{{ $value->sub_location }}</td>
                    <td>{{ $value->equipment }}</td>
                    <td>{{ $value->component }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    @if(@$value['checkSingleimage']['image'])

                    <td  width="20%"><a href="{{$value['checkSingleimage']['image']}}"><img src="{{$value['checkSingleimage']['image']}}" width="100px" height="100px" /></a></td>
                    @else
                    <td>&nbsp;</td>
                    @endif
                </tr>
                @endif
                @if($hazmatsCount != 0)
                @foreach ($value['labResults'] as $index => $hazmat)
                <tr>
                    <td>{{$value->name}}</td>
                    <td>{{ $hazmat->hazmat->short_name }}</td>

                    <td>{{ $value->location }} </td>
                    <td>{{ $value->sub_location }}</td>
                    <td>{{ $value->equipment }}</td>
                    <td>{{ $value->component }}</td>
                    <td width="8%">{{$hazmat['unit']}}</td>
                    <td width="8%">{{$hazmat['total']}}</td>
                    <td>{{$hazmat['lab_remarks']}}</td>
                    @if(@$value['checkSingleimage']['image'])

                    <td width="20%"><a href="{{$value['checkSingleimage']['image']}}"><img src="{{$value['checkSingleimage']['image']}}" width="70px" height="70px" /></a></td>
                    @else
                    <td>&nbsp;</td>
                    @endif
                </tr>
                @endforeach
                @endif
                @endforeach
            @else
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            @endif
            </tbody>
        </table>
        @endif

        @if(@$visualImage)
        <h4>Visaula Recoreds</h4><br/>
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
            @if(count($visualImage) > 0)
                @foreach(@$visualImage as $value)
                @php $hazmatsCount = count($value->labResults); @endphp

                @if($hazmatsCount == 0)
                <tr>
                    <td>{{$value->name}}</td>
                    <td>&nbsp;</td>
                    <td>{{ $value->location }} </td>
                    <td>{{ $value->sub_location }}</td>
                    <td>{{ $value->equipment }}</td>
                    <td>{{ $value->component }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    @if(@$value['checkSingleimage']['image'])

                    <td  width="20%"><a href="{{$value['checkSingleimage']['image']}}"><img src="{{$value['checkSingleimage']['image']}}" width="70px" height="70px" /></a></td>
                    @else
                    <td>&nbsp;</td>
                    @endif
                </tr>
                @endif
                @if($hazmatsCount != 0)
                @foreach ($value['labResults'] as $index => $hazmat)
                <tr>
                    <td>{{$value->name}}</td>
                    <td>{{ $hazmat->hazmat->short_name }}</td>

                    <td>{{ $value->location }} </td>
                    <td>{{ $value->sub_location }}</td>
                    <td>{{ $value->equipment }}</td>
                    <td>{{ $value->component }}</td>
                    <td width="8%">{{$hazmat['unit']}}</td>
                    <td width="8%">{{$hazmat['total']}}</td>
                    <td>{{$hazmat['lab_remarks']}}</td>
                    @if(@$value['checkSingleimage']['image'])

                    <td  width="20%"><a href="{{$value['checkSingleimage']['image']}}"><img src="{{$value['checkSingleimage']['image']}}" width="70px" height="70px" /></a></td>
                    @else
                    <td>&nbsp;</td>
                    @endif
                </tr>
                @endforeach
                @endif
                @endforeach
            @else
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            @endif
            </tbody>
        </table>
        @endif
    </div>
</div>