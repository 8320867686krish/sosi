<style>
    table {
        border: 1px solid #000;
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table td,
    th {
        border: 1px solid black;
        padding: 4px;
        text-align: left;
        font-size: 14px;
    }

    .next {
        page-break-before: always;
    }

    tbody tr:nth-child(5n) {
        page-break-after: always;
    }
</style>

<div class="container">
    @if(@$sampleImage)
    <div class="section-1-1">
        <h4>Sample Records</h4>
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
                @forelse($sampleImage as $value)
                @php
                $hazmatsCount = count($value->labResults);
                $hasImage = @$value['checkSingleimage']['image'];
                if ($hasImage) {
                    $imagePath = $value['checkSingleimage']['image'];
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . $imageData;
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
                        <a href="#"><img src="{{ $imageBase64 }}" width="100px" height="100px" /></a>
                        @else
                        &nbsp;
                        @endif
                    </td>
                </tr>
                @else
                @foreach($value['labResults'] as $index => $hazmat)
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
                        <a href="#"><img src="{{ $imageBase64 }}" width="100px" height="100px" /></a>
                        @else
                        &nbsp;
                        @endif
                    </td>
                </tr>
                @endforeach
                @endif
                @empty
                <tr>
                    <td colspan="9">No records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    @if(@$visualImage)
    <div class="section-1-1">
        <h4>Visual Records</h4>
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
                @forelse($visualImage as $value)
                @php
                $hazmatsCount = count($value->labResults);
                $hasImage = @$value['checkSingleimage']['image'];
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
                    <td><a href="#"><img src="{{ $hasImage }}" width="100px" height="100px" /></a></td>
                </tr>
                @else
                @foreach($value['labResults'] as $index => $hazmat)
                <tr>
                    <td>{{ $value->name }}</td>
                    <td>{{ $hazmat->hazmat->short_name }}</td>
                    <td>{{ $value->location }}</td>
                    <td>{{ $value->sub_location }}</td>
                    <td>{{ $value->equipment }}</td>
                    <td>{{ $value->component }}</td>
                    <td>{{ $hazmat['sample_weight'] }}</td>
                    <td>{{ $hazmat['lab_remarks'] }}</td>
                    <td><a href="#"><img src="{{ $hasImage }}" width="70px" height="70px" /></a></td>
                </tr>
                @endforeach
                @endif
                @empty
                <tr>
                    <td colspan="9">No records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>
