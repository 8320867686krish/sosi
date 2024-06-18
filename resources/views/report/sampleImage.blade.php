<style>
    table {
        border: 1px solid #000;
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table td, th {
        border: 1px solid black; /* Adds border to each cell */
        padding: 4px; /* Adds padding to cells */
        text-align: left; /* Aligns text to the left */
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
    <!-- Section 1.1 -->
    <div class="section-1-1">
        @if(@$sampleImage)
        <h4>Sample Records</h4>
        <table class="next">
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
                @if(count($sampleImage) > 0)
                @foreach($sampleImage as $value)
                    @php
                        $hazmatsCount = count($value->labResults);
                        $tdCounter = 0;
                    @endphp

                    @if($hazmatsCount == 0)
                        @php $tdCounter = 0; @endphp
                        <tr>
                            <td>{{ $value->name }}</td>
                            <td>&nbsp;</td>
                            <td>{{ $value->location }}</td>
                            <td>{{ $value->sub_location }}</td>
                            <td>{{ $value->equipment }}</td>
                            <td>{{ $value->component }}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            @if(@$value['checkSingleimage']['image'])
                                <td width="20%"><a href=""><img src="" width="100px" height="100px" /></a></td>
                            @else
                                <td>&nbsp;</td>
                            @endif
                            @php $tdCounter = 9; @endphp
                        </tr>
                    @endif

                    @if($hazmatsCount != 0)
                        @foreach($value['labResults'] as $index => $hazmat)
                            @if($tdCounter % 5 == 0)
                                </tr><tr>
                            @endif
                            <td>{{ $value->name }}</td>
                            <td>{{ $hazmat->hazmat->short_name }}</td>
                            <td>{{ $value->location }}</td>
                            <td>{{ $value->sub_location }}</td>
                            <td>{{ $value->equipment }}</td>
                            <td>{{ $value->component }}</td>
                            <td width="8%">{{ $hazmat['sample_weight'] }}</td>
                            <td>{{ $hazmat['lab_remarks'] }}</td>
                            @if(@$value['checkSingleimage']['image'])
                                <td><a href=""><img src="" width="70px" height="70px" /></a></td>
                            @else
                                <td>&nbsp;</td>
                            @endif
                            @php $tdCounter += 9; @endphp
                        @endforeach
                    @endif
                @endforeach
                @else
                <tr>
                    <td colspan="9">&nbsp;</td>
                </tr>
                @endif
            </tbody>
        </table>
        @endif

        @if(@$visualImage)
        <h4>Visual Records</h4>
        <table class="next">
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
                @if(count($visualImage) > 0)
                @foreach($visualImage as $value)
                    @php
                        $hazmatsCount = count($value->labResults);
                        $tdCounter = 0;
                    @endphp

                    @if($hazmatsCount == 0)
                        @php $tdCounter = 0; @endphp
                        <tr>
                            <td>{{ $value->name }}</td>
                            <td>&nbsp;</td>
                            <td>{{ $value->location }}</td>
                            <td>{{ $value->sub_location }}</td>
                            <td>{{ $value->equipment }}</td>
                            <td>{{ $value->component }}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            @if(@$value['checkSingleimage']['image'])
                                <td width="20%"><a href="#"><img src="http://localhost/sosi/public/images/projects/12/171678582164.jpg" width="100px" height="100px" /></a></td>
                            @else
                                <td>&nbsp;</td>
                            @endif
                            @php $tdCounter = 9; @endphp
                        </tr>
                    @endif

                    @if($hazmatsCount != 0)
                        @foreach($value['labResults'] as $index => $hazmat)
                            @if($tdCounter % 5 == 0)
                                </tr><tr>
                            @endif
                            <td>{{ $value->name }}</td>
                            <td>{{ $hazmat->hazmat->short_name }}</td>
                            <td>{{ $value->location }}</td>
                            <td>{{ $value->sub_location }}</td>
                            <td>{{ $value->equipment }}</td>
                            <td>{{ $value->component }}</td>
                            <td width="8%">{{ $hazmat['sample_weight'] }}</td>
                            <td>{{ $hazmat['lab_remarks'] }}</td>
                            @if(@$value['checkSingleimage']['image'])
                                <td><a href=""><img src="http://localhost/sosi/public/images/projects/12/171678582164.jpg" width="70px" height="70px" /></a></td>
                            @else
                                <td>&nbsp;</td>
                            @endif
                            @php $tdCounter += 9; @endphp
                        @endforeach
                    @endif
                @endforeach
                @else
                <tr>
                    <td colspan="9">&nbsp;</td>
                </tr>
                @endif
            </tbody>
        </table>
        @endif
    </div>
</div>
