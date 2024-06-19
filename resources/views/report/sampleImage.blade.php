<!-- resources/views/report/sampleImageChunk.blade.php -->
<style>
   .section-1-1 table {
        border: 1px solid #000;
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .section-1-1 table td, th {
        border: 1px solid black;
        padding: 4px;
        text-align: left;
        font-size: 14px;
    }

    .next {
        page-break-before: always;
    }

    .section-1-1 tbody tr:nth-child(5n) {
        page-break-after: always;
    }
</style>

<div class="container">
    <div class="section-1-1">
        <h4>Sample Records - Chunk</h4>
        <table>
            <thead>
                <tr>
                    <th>Sample No</th>
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
                $hasImage = !empty($value['checkSingleimage']['image']);
                if ($hasImage) {
                    $imagePath = $value['checkSingleimage']['image'];

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
                        <a href="#"><img src="{{ $imagePath }}" width="100px" height="100px" /></a>
                        @else
                        &nbsp;
                        @endif
                    </td>
                </tr>
                @else
                @foreach($value['labResults'] as $hazmat)
                <tr>
                    <td>{{ $value->name }}</td>
                    <td>{{ $hazmat->hazmat->short_name }}</td>
                    <td>{{ $value->location }}</td>
                    <td>{{ $value->sub_location }}</td>
                    <td>{{ $value->equipment }}</td>
                    <td>{{ $value->component }}</td>
                    <td>{{ $hazmat['sample_weight'] }}</td>
                    <td>{{ $hazmat['lab_remarks'] }}</td>
                    <td>
                        @if($hasImage)
                        <a href="#"><img src="{{$imagePath}}" width="100px" height="100px" /></a>
                        @else
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
