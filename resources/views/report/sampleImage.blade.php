<!-- resources/views/report/sampleImageChunk.blade.php -->
<style>
    .section-1-1 table {
        border: 1px solid #000;
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .section-1-1 table td,
    th {
        border: 1px solid black;
        padding: 4px;
        text-align: left;
        font-size: 14px;
    }
    .section-1-1 table td{
        height:150px;
    }
    .next {
        page-break-before: always;
    }

    .section-1-1 tbody tr:nth-child(3n) {
        page-break-after: always;
    }
</style>

<div class="container">
    <div class="section-1-1">
        @if(@$show && $show == true)
        <h2>Appendix-3 Onboard Sampling & Lab Analysis Record</h2>
        @endif
        <h4>{{$title}}</h4>
        <table>
            <thead>
                <tr>
                    <th>{{$numberColoum}}</th>
                    <th>Material</th>
                    <th colspan="2">Location</th>
                    <th>Equipment</th>
                    <th>Component</th>
                    <th>Sample Quantity</th>
                    <th>Remarks</th>
                    <th>Photograph</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chunk as $value)
                @php
                $hazmatsCount = count($value->labResults);
                $checkImage=$value['check_image']->slice(0, 2);
                $a=$checkImage->toArray();

                $hasImage = !empty($a[0]['image']);
                if ($hasImage) {
                $imagePath = $a[0]['image'];
                if (@$a[1]['image']){
                    $imagePath1 = $a[1]['image'];

                }else{
                    $imagePath1 = "#";
                }


                }
                @endphp

                @if($hazmatsCount == 0)
                <tr>
                    <td>{{ $value->name }}</td>
                    <td>&nbsp;</td>
                    <td>{{ $value->location }}</td>
                    <td>{{ $value->sub_location }}</td>
                    <td>{{ $value->equipment }}</td>
                    <td>{{ $value->component }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        @if($hasImage)
                        <a href="{{ $imagePath1 ?? '#' }}">
                            <img src="{{ $imagePath }}" width="130px" height="130px" />
                        </a>
                        @else
                        &nbsp;
                        @endif
                    </td>
                </tr>
                @else
                @foreach($value['labResults'] as $hazmat)
                @php
                if($hazmat->type == 'Contained' || $hazmat->type == 'PCHM'){
                $color = 'style="color:#FF0000"';

                }else{
                    $color = 'style="color:#000000"';
                }
                @endphp
                <tr>
                <td {!! $color !!}>{{ $value->name }}</td>
                <td {!! $color !!}>{{ $hazmat->hazmat->short_name }}</td>
                <td {!! $color !!}>{{ $value->location }}</td>
                <td {!! $color !!}>{{ $value->sub_location }}</td>
                <td {!! $color !!}>{{ $value->equipment }}</td>
                <td {!! $color !!}>{{ $value->component }}</td>
                <td {!! $color !!}>{{ $hazmat['sample_weight'] }}</td>
                <td {!! $color !!}>{{ $hazmat['lab_remarks'] }}</td>
                <td {!! $color !!}>
                        @if($hasImage)
                        <a href="{{ $imagePath1 ?? '#' }}">
                            <img src="{{ $imagePath }}" width="150px" height="150px" />
                        </a> @else
                        &nbsp;
                        @endif
                    </td>
                </tr>
                @endforeach
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>