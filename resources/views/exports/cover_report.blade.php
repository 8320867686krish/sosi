<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>COVER</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" valign="middle" align="center" height="50"
                    style="font-size: 18px; background-color: #DADADA;">
                    <b>{{ $title }}</b>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </thead>
    </table>

    <table>
        <tbody>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Name of Ship</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->ship_name ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">IMO Number</td>
                <td colspan="4" align="left" style="border: 2px solid #000000">{{ $project->imo_number ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Gross Tonnage</td>
                <td colspan="4" align="left" style="border: 2px solid #000000">{{ $project->gross_tonnage ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">L x B x D</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->x_breadth_depth ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Date of Delivery</td>
                <td colspan="4" style="border: 2px solid #000000">{{ !empty($project->delivery_date) ? date('d-m-Y', strtotime($project->delivery_date)) : '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" align="center" style="border: 2px solid #000000"><b>&nbsp;</b></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" align="center" style="border: 2px solid #000000"><b>Ship Owner Details</b></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Ship Owner Name</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->client->owner_name ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Ship Owner Email</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->client->owner_email ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Ship Owner Phone</td>
                <td colspan="4" align="left" style="border: 2px solid #000000">{{ $project->client->owner_phone ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Ship Owner Address</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->client->owner_address ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" align="center" style="border: 2px solid #000000"><b>&nbsp;</b></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" align="center" style="border: 2px solid #000000"><b>Ship Manager Details</b></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Ship Manager Name</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->client->manager_name ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Ship Manager Email</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->client->manager_email ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Ship Manager Phone</td>
                <td colspan="4" align="left" style="border: 2px solid #000000">{{ $project->client->manager_phone ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Ship Manager Address</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->client->manager_address ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" style="border: 2px solid #000000">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">IHM Service Supply Details</td>
                <td colspan="4" style="border: 2px solid #000000">S.O.S. Recycling Pvt. Ltd.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" align="center" style="border: 2px solid #000000"><b>Survey Details</b></td>
                {{-- <td colspan="4" style="border: 2px solid #000000">&nbsp;</td> --}}
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Survey Location Name</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->survey_location_name ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Survey Location Address</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->survey_location_address ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Survey Type</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->survey_type ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Survey Date</td>
                <td colspan="4" style="border: 2px solid #000000">{{ !empty($project->survey_date) ? date('d-m-Y', strtotime($project->survey_date)) : '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" style="border: 2px solid #000000">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" align="center" style="border: 2px solid #000000"><b>Laboratory</b></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Laboratory1</td>
                <td colspan="4" style="border: 2px solid #000000">{{$project->laboratorie1}}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Laboratory2</td>
                <td colspan="4" style="border: 2px solid #000000">{{$project->laboratorie2}}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
