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
                <th style="font-size: 14px; background-color: #DADADA;" valign="middle" align="center"><b>N</b></th>
                <th style="font-size: 14px; background-color: #DADADA;" colspan="2" valign="middle" align="center"><b>Hazardous Materials</b></th>
                <th style="font-size: 14px; background-color: #DADADA;" valign="middle" align="center"><b>Location</b></th>
                <th style="background-color: #DADADA;"></th>
                <th style="background-color: #DADADA;"></th>
                {{-- <th style="background-color: #DADADA;"></th> --}}
                <th width="20" style="background-color: #DADADA;"></th>
                <th style="font-size: 14px; background-color: #DADADA;" valign="middle" align="center"><b>Manufacture/<br>Brand Name</b></th>
                <th style="font-size: 12px; background-color: #DADADA;" valign="middle" align="center"><b>Document<br>Analysis Result</b></th>
                <th style="font-size: 14px; background-color: #DADADA;" colspan="4" valign="middle" align="center"><b>Check</b></th>
                <th style="font-size: 14px; background-color: #DADADA;" valign="middle" align="center"><b>References/Remarks</b></th>
            </tr>

            <tr height="25">
                <th style="background-color: #DADADA;"></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Key</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Table</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Deck</b></th>
                {{-- <th style="background-color: #DADADA;" width="15" height="20" valign="middle" align="center"><b>Compartment</b></th> --}}
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Equipment</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Component</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Approx Quantity</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Procedure</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Result</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>No.</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"><b>Name</b></th>
                <th style="background-color: #DADADA;" valign="middle" align="center"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1;
            @endphp
            @foreach ($checks as $check)
                @foreach ($check->check_hazmats as $checkHazmat)
                    <tr height="25">
                        <td valign="middle" align="center" style="font-size: 14px;">{{$counter}}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{$checkHazmat->hazmat->short_name}}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{$checkHazmat->hazmat->table_type}}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{$check->deck->name}}</td>
                        {{-- <td valign="middle" align="center" style="font-size: 14px;"></td> --}}
                        <td valign="middle" align="center" style="font-size: 14px;">{{$check->equipment}}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{$check->component}}</td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{$checkHazmat->type}}</td>
                        <td valign="middle" align="center" style="font-size: 14px;">{{ $check->type == 'sample' ? 'sampling' : 'visual' }}</td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="center" style="font-size: 14px;"></td>
                        <td valign="middle" align="left" style="font-size: 14px;">Lorem ipsum dolor sit amet consectetur adipisicing elit.</td>
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