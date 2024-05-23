<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General styles */
        body {
            font-family: 'Arial';
        }

        .container {
            padding: 20px;
        }

        /* Styles for section 1.1 */
        .section-1-1 {
            padding-top: 20px;
        }

        .section-1-1 h3 {
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }

        .section-1-1 h4 {
            font-weight: bold;
            font-size: 15px;
            color: #333;
        }

        .section-1-1 p {
            font-size: 14px;
            color: #555;
            line-height: 1.5;
        }

        /* Styles for section 1.2 */



        .section-1-1 table {
            border: 1px solid #000;
            width: 100%;
            border-collapse: collapse;
            /* Ensures borders do not have gaps */
        }

        .section-1-1 table td,
        th {
            border: 1px solid black;
            /* Adds border to each cell */
            padding: 8px;
            /* Adds padding to cells */
            text-align: left;
            /* Aligns text to the left */
            font-size: 14px;
        }

        .section-1-1 table td {
            border: 1px solid black;
            /* Adds border to each cell */
            padding: 8px;
            /* Adds padding to cells */
            font-size: 14px;
        }

        .section-1-1 table th {
            background-color: #4C94D8;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Section 1.1 -->
        <h3>2. Inventory of Hazardous Materials (IHM) Part-1 </h3>
        <div class="section-1-1">
            <h3>
                <center>Part-1</center>
            </h3>
            <center>
                <h4>Hazardous materials contained in the ship's structure and equipment</h4>
            </center>
            @if($filteredResults1)
            <h4>I-1 – Paints and coating systems containing materials listed in table A and table B of appendix 1 of these guidelines</h4>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Application of paint</th>
                        <th>Name of paint</th>
                        <th>Location</th>
                        <th>Materials (classification in appendix 1 of MEPC.379(80) & Annex-B of EMSA Guidelines)</th>
                        <th colspan="2">Approximate quantity</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filteredResults1 as $value)
                    <tr>
                        <td>{{$loop->index}}</td>
                        <td>{{$value['check']['equipment']}}</td>
                        <td>{{$value['check']['component']}}</td>
                        <td>{{$value['check']['location']}}</td>
                        <td>{{$value['hazmat']['name']}}</td>
                        <td>{{$value['total']}}</td>
                        <td>{{$value['unit']}}</td>
                        <td>{{$value['remarks']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            @if($filteredResults2)
            <div style="padding-top: 20px;">
                <h4>I-2 Equipment and machinery containing materials listed in table A and table B of appendix 1 of these guidelines</h4>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Application of paint</th>
                            <th>Name of paint</th>
                            <th>Location</th>
                            <th>Materials (classification in appendix 1 of MEPC.379(80) & Annex-B of EMSA Guidelines)</th>
                            <th colspan="2">Approximate quantity</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filteredResults2 as $value)
                        <tr>
                            <td>{{$loop->index}}</td>
                            <td>{{$value['check']['equipment']}}</td>
                            <td>{{$value['check']['component']}}</td>
                            <td>{{$value['check']['location']}} @if($value['check']['sub_location'])
        {{ ',' .$value['check']['sub_location'] }}
    @endif</td>
                            <td>{{$value['hazmat']['name']}}</td>
                            <td>{{$value['total']}}</td>
                            <td>{{$value['unit']}}</td>
                            <td>{{$value['remarks']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
                @if($filteredResults3)
            <div style="padding-top: 20px;">
                <h4>I-3 Structure and hull containing materials listed in table A and table B of appendix 1 of these guidelines</h4>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Application of paint</th>
                            <th>Name of paint</th>
                            <th>Location</th>
                            <th>Materials (classification in appendix 1 of MEPC.379(80) & Annex-B of EMSA Guidelines)</th>
                            <th colspan="2">Approximate quantity</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filteredResults3 as $value)
                        <tr>
                            <td>{{$loop->index}}</td>
                            <td>{{$value['check']['equipment']}}</td>
                            <td>{{$value['check']['component']}}</td>
                            <td>{{$value['check']['location']}}</td>
                            <td>{{$value['hazmat']['name']}}</td>
                            <td>{{$value['total']}}</td>
                            <td>{{$value['unit']}}</td>
                            <td>{{$value['remarks']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif


        </div>


        <!-- Section 1.2 -->


    </div>
</body>

</html>