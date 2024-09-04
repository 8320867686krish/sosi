<div class="container">
    <!-- Section 1.1 -->
    <h2>Appendix-1 Visual Sampling Check Plan & Summary</h2>

    <div class="section-1-1">
        <h4>Abbreviation Used</h4>

        <table>
            <thead>
                <tr>
                <th colspan="2">Result of Documentation Analysis</th>
                <th colspan="2">Procedure of Check</th>
                <th colspan="2">Result of Check</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>N</td>
                    <td>Not Contained</td>
                    <td>V</td>
                    <td>Visually Verified</td>
                    <td>N</td>
                    <td>Not Contained</td>
                </tr>
                <tr>
                    <td>C</td>
                    <td>Contained</td>
                    <td rowspan="2">S</td>
                    <td rowspan="2">Sample Taken</td>
                    <td rowspan="2">C</td>
                    <td rowspan="2">Contained</td>
                </tr>
                <tr>
                    
                    <td>Unknown</td>
                    <td>Unable to conclude</td>
                </tr>
                <tr>
                    <td rowspan="2">Assumption</td>
                    <td rowspan="2">Assumptions based on referred guidelines/reports</td>
                 
                    <td  rowspan="2">PCHM</td>
                    <td  rowspan="2">Potentially Contained Hazardous material</td>
                    <td>BTV</td>
                    <td>Below Threshold Value</td>
                </tr>
              
            </tbody>

        </table>
        <br/>
        <table>
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Table A/B</th>
                    <th>HazMat</th>
                    <th>Location</th>
                    <th>Equipment</th>
                    <th>Component</th>
                    <th>Document Analysis</th>
                    <th>Procedure</th>
                    <th>Results</th>
                </tr>

            </thead>
            <tbody>
                @php $count = 1; @endphp

                @foreach($ChecksList as $value)
                @if(count($value['checks']) > 0)

                @foreach ($value->checks as $check)
                @php $hazmatsCount = count($check->check_hazmats); @endphp
                @if ($hazmatsCount == 0)
                <tr>
                <td>{{$check['name']}}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>{{ $check->location }} @if($check->sub_location){{ ',' . $check->sub_location }}
                        @endif</td>
                    <td>{{ $check->equipment }}</td>
                    <td> {{ $check->component }}</td>
                    <td>&nbsp;</td>
                    <td>{{ $check->type }}</td>
                    <td>&nbsp;</td>
                </tr>
                @php $count++; @endphp
                @endif
                @foreach ($check->check_hazmats as $index => $hazmat)
                @php
                if($hazmat->final_lab_result == 'Contained' || $hazmat->final_lab_result == 'PCHM'){
                $color = 'style="color:#FF0000"';

                }else{
                    $color = 'style="color:#000000"';
                }
                @endphp
                <tr>

                <td  {!! $color !!}>{{$check['name']}}</td>

             <td  {!! $color !!}>{{ $hazmat->hazmat->table_type}}</td>
                    <td  {!! $color !!}>{{ $hazmat->hazmat->short_name }}</td>
                    <td {!! $color !!}>{{ $check->location }} @if($check->sub_location){{ ',' . $check->sub_location }}
                        @endif</td>
                    <td {!! $color !!}>{{ $check->equipment }}</td>
                    <td  {!! $color !!}> {{ $check->component }}</td>
                    @if($hazmat->image)
                    <td  {!! $color !!}><a href="{{$hazmat->image}}" target="_blank">Known</a></td>
                    @else
                    <td  {!! $color !!}>UnKnown</td>
                    @endif

                    <td {!! $color !!}>{{ ($check->type == 'visual') ? 'V' : 'S' }}</td>
                    <td {!! $color !!}>{{ $hazmat->final_lab_result ? $hazmat->final_lab_result :'Not Contained' }}</td>

                </tr>
                @php $count++; @endphp
                @endforeach
                @endforeach
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <h2>Recored of changes in checks during onboard survey</h2>
    <table>
        <thead>
            <tr>
                <th>S.N</th>
                <th>Initial Method Of Check</th>
                <th>Changed To</th>
                <th>Remark</th>
            </tr>
            <tbody>
                @foreach($ChangeCheckList as $value)
                <tr>
                    <td>{{$value['name']}}</td>
                    <td>{{$value['type']}}</td>
                    <td>
                    @if($value->markAsChange == 1)
                    {{ $value->type == 'smaple' ? 'visual' : 'sample' }}
                    @else
                    {{ $value->type }}
                    @endif
                </td>
                <td>
                    {{$value->labResultsChange}}
                </td>
                </tr>
                @endforeach
            </tbody>
        </thead>
    </table>
</div>