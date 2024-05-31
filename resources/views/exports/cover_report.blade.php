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
                <td colspan="7" align="center" style="border: 2px solid #000000"><b>Ship Particulars Details</b></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Name of Ship</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->ship_name ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">IMO Number</td>
                <td colspan="4" align="left" style="border: 2px solid #000000">{{ $project->imo_number ?? '' }}
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Call Sign</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->call_sign ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Type of ship</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->ship_type ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Port Of Registry</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->port_of_registry ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Vessel Class</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->vessel_class ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">IHM Certifying Class</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->ihm_class ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Flag of ship</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->flag_of_ship ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Date of Delivery</td>
                <td colspan="4" style="border: 2px solid #000000">
                    {{ !empty($project->delivery_date) ? date('d-m-Y', strtotime($project->delivery_date)) : '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Building Yard Details</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->building_details }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">L x B x D</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->x_breadth_depth ?? '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Gross Tonnage</td>
                <td colspan="4" align="left" style="border: 2px solid #000000">{{ $project->gross_tonnage ?? '' }}
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Vessel Previous Name</td>
                <td colspan="4" style="border: 2px solid #000000">{{ $project->vessel_previous_name }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Ship Image</td>
                <td colspan="4" style="border: 2px solid #000000"><a href="{{$project->image}}">Click Here</a></td>
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
                <td colspan="4" align="left" style="border: 2px solid #000000">
                    {{ $project->client->owner_phone ?? '' }}</td>
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
                <td colspan="4" align="left" style="border: 2px solid #000000">
                    {{ $project->client->manager_phone ?? '' }}</td>
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
                <td colspan="7" align="center" style="border: 2px solid #000000"><b>IHM Service Supplier Details</b></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Name</td>
                <td colspan="4" style="border: 2px solid #000000">Solution Ovelall For Shipping (S.O.S) India Pvt. Ltd.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Address</td>
                <td colspan="4" style="border: 2px solid #000000">SOS INDIA, G-46, Agrawal Trade Center Sec-11, CBD Belapur, Navi Mumbai - 400614</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" style="border: 2px solid #000000">Email</td>
                <td colspan="4" style="border: 2px solid #000000">info@sosindi.com</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" rowspan="2" valign="middle" style="border: 2px solid #000000">Approved By</td>
                <td colspan="4" style="border: 2px solid #000000">Lloyed's Register (LR Class)</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                {{-- <td colspan="3" style="border: 2px solid #000000"></td> --}}
                <td colspan="4" style="border: 2px solid #000000">India Register Of Shipping (IR Class)</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" style="border: 2px solid #000000">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" align="center" style="border: 2px solid #000000"><b>Survey Details</b></td>
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
                <td colspan="4" style="border: 2px solid #000000">
                    {{ !empty($project->survey_date) ? date('d-m-Y', strtotime($project->survey_date)) : '' }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" style="border: 2px solid #000000">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="7" align="center" style="border: 2px solid #000000"><b>Laboratory</b></td>
            </tr>
            @if (!empty($project->laboratorie1))
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" style="border: 2px solid #000000">Laboratory</td>
                    <td colspan="4" style="border: 2px solid #000000">{{ $project->laboratorie1 }}</td>
                </tr>
            @endif
            @if (!empty($project->laboratorie2))
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3" style="border: 2px solid #000000">Laboratory 2</td>
                    <td colspan="4" style="border: 2px solid #000000">{{ $project->laboratorie2 }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
