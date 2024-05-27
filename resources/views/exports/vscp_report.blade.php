<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VSCP</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th style="font-size: 14px; background-color: #DADADA;" valign="middle" align="center"><b>NO</b></th>
                <th style="font-size: 14px; background-color: #DADADA;" valign="middle" align="center"><b></b></th>
                <th style="font-size: 14px; background-color: #DADADA;" colspan="2" valign="middle" align="center">
                    <b>Hazardous<br> Materials</b></th>
                <th style="font-size: 14px; background-color: #DADADA;" valign="middle" align="center"><b>Location</b>
                </th>
                <th style="background-color: #DADADA;"></th>
                <th style="background-color: #DADADA;"></th>
                {{-- <th style="background-color: #DADADA;"></th> --}}
                <th width="20" style="background-color: #DADADA;"></th>
                <th style="font-size: 14px; background-color: #DADADA;" valign="middle" align="center">
                    <b>Manufacture/<br>Brand Name</b></th>
                <th style="font-size: 12px; background-color: #DADADA;" valign="middle" align="center">
                    <b>Document<br>Analysis Result</b></th>
                <th style="font-size: 14px; background-color: #DADADA;" colspan="3" valign="middle" align="center"><b>Check</b></th>
                <th style="font-size: 14px; background-color: #DADADA;" valign="middle" align="center"><b>References/Remarks</b></th>
                <th style="font-size: 14px; background-color: #DADADA;" valign="middle" align="center"><b>Recommendation</b></th>
            </tr>

            <tr height="25">
                <th style="background-color: #DADADA;"></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Name</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>HM</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Table</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Location Name</b></th>
                {{-- <th style="background-color: #DADADA;" width="15" height="20" valign="middle" align="center"><b>Compartment</b></th> --}}
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Equipment</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Component</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Approx Quantity</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Procedure</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Result</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Remarks</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1;
            @endphp
            @foreach ($checks as $check)
                @php $hazmatsCount = count($check->check_hazmats); @endphp
                @if ($hazmatsCount == 0)
                    <tr height="25">
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $counter }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->name }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->location }@if($check->sub_location)
        {{ ',' . $check->sub_location }}}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->equipment }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->component }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->type }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="left" style="font-size: 14px;">{{$check->remarks}}</td>
                        <td valign="middle" align="left" style="font-size: 14px;">{{$check->recommendation}}</td>
                    </tr>
                    @php $counter++; @endphp
                @endif
                @foreach ($check->check_hazmats as $checkHazmat)
                    <tr height="25">
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $counter }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->name }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">
                            {{ $checkHazmat->hazmat->short_name }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">
                            {{ $checkHazmat->hazmat->table_type }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->location }}@if($check->sub_location)
        {{ ',' . $check->sub_location }}</td>
                        {{-- <td valign="middle" align="center" style="font-size: 14px;"></td> --}}
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->equipment }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->component }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $checkHazmat->type }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->type }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $checkHazmat->remarks }}</td>
                        <td valign="middle" align="left" style="font-size: 14px;">{{$check->remarks}}</td>
                        <td valign="middle" align="left" style="font-size: 14px;">{{$check->recommendation}}</td>
                    </tr>
                    @php
                        $counter++;
                    @endphp
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>

</html>
