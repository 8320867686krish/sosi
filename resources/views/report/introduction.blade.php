<div class="container">
        <!-- Section 1.1 -->
        <h2>1. Introduction to Project</h2>
        <div class="section-1-1">
            <h3>1.1 Scope, Exemption and Exclusions</h3>
            <p>The Scope of this document is to prepare an Inventory of Hazardous Materials as per IMO HKC- MEPC 379(80) and EUSRR-EMSA guidelines for the vessel <b>{{$projectDetail['ship_name']}}</b> on behalf of <b>{{$projectDetail['client']['manager_name']}}</b>.</p>
            <p>The scope of this IHM report is delimited by the availability of documentation, acknowledging that certain records may not be accessible. Additionally, due to operational constraints, there are limitations on inspecting all spaces and equipment aboard the vessel while it remains in operation.</p>
            <p>SOS India Pvt Ltd does not warrant or assume any kind of liability for the up-to-date nature, accuracy, completeness or quality of the HazMat weight calculation provided. Liability claims against any member of SOS India Pvt Ltd in relation to any loss or damage arising out of or in connection with the use or non-use of HazMat weight calculation provided, including the use of incorrect or incomplete HazMat weight calculation data, are excluded to the fullest extent permissible by law. All weight calculation data may be subject to alteration and are non-binding. Each SOS India Pvt Ltd member expressly reserves the right without notice to change, supplement or delete parts of the HazMat weight calculation data or to stop the publication and usage temporarily or definitively.</p>
        </div>

        <!-- Section 1.2 -->
        <div class="section-1-2">
            <h3>1.2 Abbreviations & Normative References</h3>
            <p>Abbreviation :</p>
            <ul>
                @foreach($hazmets as $value)
                <li>{{$value['id']}}. {{$value['short_name']}} = {{$value['name']}}</li>
                @endforeach
            </ul>
            <br />
            <p style="padding-top:10px;">Normative Reference:</p>
            <ul>
                <li>1. Hong Kong International Convention for the Safe and Environmentally Sound Recycling of Ships, 2009 (SR/CONF/45)</li>
                <li>2. 2023 Guidelines for the Development of the Inventory of Hazardous Materials (MEPC. 379(80)) </li>
                <li>3. EU Regulation on Ship Recycling, Regulation (EU) No1257/2013</li>
                <li>4. EMSA’s Best Practice Guidance on the Inventory of Hazardous Material, 2016-10-28</li>
                <li>5. SOLAS regulation II -1/3-5 new amendments concerning the new installation of asbestos-containing material, MSC.282(86)</li>
                <li>6. MSC. 1/Circ. 1426 Unified Interpretation of SOLAS Regulation II- 1/3-5</li>
                <li>7. MSC. 1/Circ.1374 Information on Prohibiting the use of asbestos onboard ships</li>
                <li>8. MSC. 1/Circ.1379 Unified Interpretation of SOLAS Regulation II- 1/3-5</li>
            </ul>
        </div>

        <div class="section-1-3 next">
            <h3> 1.3 Project Particular </h3>
            <table>

                <tr>
                    <td>IMO number</td>
                    <td> {{$projectDetail['imo_number']}}</td>
                </tr>
                <tr>
                    <td>Name of Ship</td>
                    <td> {{$projectDetail['ship_name']}}</td>
                </tr>
                <tr>
                    <td>Flag</td>
                    <td> {{$projectDetail['flag_of_ship']}}</td>
                </tr>
                <tr>
                    <td>Port of Registry Kunching</td>
                    <td> {{$projectDetail['port_of_registry']}}</td>
                </tr>
                <tr>
                    <td>Type of Vessel</td>
                    <td> {{$projectDetail['vessel_class']}}</td>
                </tr>
                <tr>
                    <td>Building Yard Details</td>
                    <td> {{$projectDetail['building_details']}}</td>
                </tr>
                <tr>
                    <td>Date of delivery</td>
                    <td>{{$projectDetail['delivery_date']}}</td>

                </tr>
                <tr>
                    <td>IHM Certifying Class</td>
                    <td>{{$projectDetail['ihm_class']}}</td>

                </tr>
                <tr>
                    <td>Dimensions (L x B x D)</td>
                    <td>{{$projectDetail['x_breadth_depth']}}</td>
                </tr>
                <tr>
                    <td>Gross Tonnage (GT)</td>
                    <td>{{$projectDetail['gross_tonnage']}}</td>
                </tr>
            </table>
        </div>

        <div class="section-1-4 next">
            <h3> 1.4 Executive Summary</h3>
            <p style="padding-bottom:25px">2. The onboard visual/sampling check was carried out at <b>{{$projectDetail['survey_location_name']}}</b> by IHM expert {{$projectDetail['surveyorName']}} on <b>{{$projectDetail['survey_date']}}</b> as per the visual/sampling check plan. Sampling points on ship were marked/labeled with check point numbers same as mentioned on sampling bag . The collected samples were appropriate bagged/packed and sent to the <b>{{$projectDetail['laboratorie1']}}</b> for the analysis. Following is details number of sampling & visual checks carried out for the vessel:</p>

            <table>
                <thead>
                    <tr>
                        <th colspan="3" valign="middle" align="center">Hazardous Material</th>
                        <th colspan="3" valign="middle" align="center">Number of Checks</th>
                    </tr>
                    <tr>
                        <th valign="middle"><b>Table</b></th>
                        <th valign="middle"><b>HM</b></th>
                        <th valign="middle"><b>Name</b></th>
                        <th valign="middle"><b>Sampleing</b></th>
                        <th valign="middle"><b>Visual</b></th>
                        <th valign="middle"><b>Total</b></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total = 0;
                    $sampling = 0;
                    $visual = 0;
                    @endphp
                    @foreach ($hazmets as $hazmat)
                    <tr>

                        <td valign="middle">
                            {{ $hazmat->table_type }}
                        </td>
                        <td valign="middle">{{ $hazmat->short_name }}</td>
                        <td>
                            {{ $hazmat->name }}
                        </td>
                        <td valign="middle" align="center">{{ $hazmat->sample_count }}
                        </td>
                        <td valign="middle" align="center">{{ $hazmat->visual_count }}
                        </td>
                        <td valign="middle" align="center">{{ $hazmat->check_type_count }}
                        </td>
                    </tr>
                    @php
                    $sampling += $hazmat->sample_count;
                    $visual += $hazmat->visual_count;
                    $total += $hazmat->check_type_count;
                    @endphp
                    @endforeach
                    <tr>
                        <td colspan="3" style="border: 2px solid #000000"></td>
                        <td valign="middle" align="center" style="border: 2px solid #000000"><b>{{ $sampling }}</b></td>
                        <td valign="middle" align="center" style="border: 2px solid #000000"><b>{{ $visual }}</b></td>
                        <td valign="middle" align="center" style="border: 2px solid #000000"><b>{{ $total }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>