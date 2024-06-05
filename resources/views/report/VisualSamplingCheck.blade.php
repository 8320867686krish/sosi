<div class="container">
    <!-- Section 1.1 -->
    <h2>Appendix-1 Visual Sampling Check Plan</h2>
    <div class="section-1-1">
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
                @foreach($ChecksList as $value)
                @if(count($value['checks']) > 0)
                @php $count = 1; @endphp

                @foreach ($value->checks as $check)
                @php $hazmatsCount = count($check->check_hazmats); @endphp
                @if ($hazmatsCount == 0)
                <tr>
                    <td>{{$count}}</td>
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
                <tr style="background-color: {{ ($hazmat->type == 'Contained' || $hazmat->type == 'PCHM') ? '#CC3300' : 'transparent' }};color:#fff;">

                    <td>{{$count}}</td>
                    <td>{{ $hazmat->hazmat->table_type }}</td>
                    <td>{{ $hazmat->hazmat->short_name }}</td>
                    <td>{{ $check->location }} @if($check->sub_location){{ ',' . $check->sub_location }}
                        @endif</td>
                    <td>{{ $check->equipment }}</td>
                    <td> {{ $check->component }}</td>
                    @if($hazmat->image)
                        <td>Known</td>
                    @else
                    <td>UnKnown</td>
                    @endif
                    
                    <td>{{ ($check->type == 'visual') ? 'V' : 'S' }}</td>
                    <td>{{ substr($hazmat->type, 0, 1) }}</td>

                </tr>
                @php $count++; @endphp
                @endforeach
                @endforeach
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>