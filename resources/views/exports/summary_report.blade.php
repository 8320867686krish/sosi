<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SUMMARY</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <th colspan="3" valign="middle" align="center" style="border: 2px solid #000000; font-size: 14px;"><b>Hazardous Material</b></th>
                <th colspan="3" valign="middle" align="center" style="border: 2px solid #000000; font-size: 14px;"><b>Number of Checks</b></th>
            </tr>
            <tr>
                <th></th>
                <th valign="middle" align="center" style="border: 2px solid #000000" width="12"><b>Table</b></th>
                <th valign="middle" align="center" style="border: 2px solid #000000"><b>Key</b></th>
                <th valign="middle" align="center" style="border: 2px solid #000000" width="40"><b>Name</b></th>
                <th valign="middle" align="center" style="border: 2px solid #000000"><b>Sampling</b></th>
                <th valign="middle" align="center" style="border: 2px solid #000000"><b>Visual/<br>Sample</b></th>
                <th valign="middle" align="center" style="border: 2px solid #000000"><b>Total</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach($hazmats as $hazmat)
                <tr>
                    <td></td>
                    <td style="border: 2px solid #000000" width="12">{{ $hazmat->table_type }}</td>
                    <td style="border: 2px solid #000000">{{ $hazmat->short_name }}</td>
                    <td style="border: 2px solid #000000" width="40">{{ $hazmat->name }}</td>
                    <td style="border: 2px solid #000000">83</td>
                    <td style="border: 2px solid #000000">72</td>
                    <td style="border: 2px solid #000000">{{ $hazmat->check_type_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
