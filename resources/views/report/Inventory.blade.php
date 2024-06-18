<style>
     @page {
            size: A4 portrait;
        }
    body {
        font-family: 'Arial';
    }

    .container {
        padding: 10px;
    }

    /* Styles for section 1.1 */

    .section-1-1 h3 {
        font-weight: bold;
        font-size: 16px;
        color: #000;
    }

    .next {
        page-break-before: always;

    }

    .section-1-1 p {
        font-size: 14px;
        color: #000;
        line-height: 1.5;
    }

    /* Styles for section 1.2 */
    .section-1-2 {
        padding-top: 20px;
    }

    .section-1-2 h3 {
        font-weight: bold;
        font-size: 16px;
        color: #000;
    }

    .section-1-2 ul,
    .ulIteam {
        font-size: 14px;
        color: #000;
        line-height: 1.5;
        padding-top: 10px;
        margin-bottom: 5px;
        /* Add some margin between list items */

    }

    .ulIteam li {
        line-height: 30px;
        padding-top: 5px;
    }

    .section-1-2 li,
    .section-1-1 li {
        line-height: 20px;
        padding-top: 5px;
    }

    .section-1-2 p {
        font-size: 14px;
        color: #000;
        line-height: 1.5;
        margin-bottom: 10px;
        /* Add some margin after the paragraphs */
    }

    .section-1-3 {
        padding-top: 20px;
    }

    .section-1-3 table {
        border: 1px solid #000;
        width: 100%;
        border-collapse: collapse;
        /* Ensures borders do not have gaps */

    }

    .section-1-3 table td {
        border: 1px solid black;
        /* Adds border to each cell */
        padding: 8px;
        /* Adds padding to cells */
        text-align: left;
        /* Aligns text to the left */
        font-size: 14px;
        padding: 5px;
    }


    .section-1-3 h3 {
        font-weight: bold;
        font-size: 16px;
        color: #000;
    }

    .section-1-4 h3 {
        font-weight: bold;
        font-size: 16px;
        color: #000;
    }

    .section-1-4 {
        padding-top: 20px;
    }

    .section-1-4 table {
        border: 1px solid #000;
        width: 100%;
        border-collapse: collapse;
        /* Ensures borders do not have gaps */
    }

    .section-1-1 table {
        border: 1px solid #000;
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;

    }

    .setFont {
        font-size: 16px;
    }

    .sufont {
        font-size: 14px;
        color: #000 !important;
        font-weight: 700;
    }



    .section-1-3 table td,
    th {
        border: 1px solid black;
        /* Adds border to each cell */
        padding: 4px;
        /* Adds padding to cells */
        text-align: left;
        /* Aligns text to the left */
        font-size: 14px;
    }

    .section-1-4 table td {
        border: 1px solid black;
        /* Adds border to each cell */
        padding: 4px;
        /* Adds padding to cells */
        font-size: 12px;
        padding: 5px;
    }

    .section-1-1 table td,
    th {
        border: 1px solid black;
        /* Adds border to each cell */
        padding: 4px;
        /* Adds padding to cells */
        text-align: left;
        /* Aligns text to the left */
        font-size: 12px;
        /* page-break-inside: avoid; */
    }

    .section-1-4 table th {
        background-color: #4C94D8;
    }

    .section-1-1 table th {
        background-color: #4C94D8;
    }

    .displayed {
        display: block;
        margin-left: auto;
        margin-right: auto
    }

    .light-green {
        background-color: #00F16C;
        color: #fff;
        line-height: 20px;

    }

    .highRisk {
        background-color: #FFC000;
        color: #000;
        line-height: 20px;

    }

    .veryHigh {
        background-color: #CC3300;
        color: #fff;
        line-height: 20px;

    }

    .modirateRisk {
        background-color: #FFFF00;
        color: #000;
        line-height: 20px;

    }

    .critical {
        background-color: #FF0000;
        color: #fff;
        line-height: 20px;
    }

    .unit-column {
        width: 20%;
    }

    .value-column {
        width: 60%;
    }

    .density-column {
        width: 40%;
    }

    .maincontnt {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .image-container {
        position: relative;
        display: inline-block;
        margin: 20px;
    }

    .dot {
        position: absolute;
        width: 12px;
        height: 12px;
        border: 2px solid red;
        background: red;
        border-radius: 50%;
        text-align: center;
        line-height: 20px;
    }

    .tooltip {
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 5px;
        border-radius: 5px;
        white-space: nowrap;
        z-index: 1;
        /* Ensure tooltip is above the dots and lines */
        color: blue;
    }

    .line {
        position: absolute;
        width: 1px;
        background-color: red;
    }
  
</style>

<div class="container ">
    <!-- Section 1.1 -->
    <h2>2. Inventory of Hazardous Materials (IHM) Part-1 </h2>
    <div class="section-1-1">
            <h5 style="text-align: center;">
                Part-1
            </h5>

            <h4 style="text-align: center;">Hazardous materials contained in the ship's structure and equipment</h4>

        @if(@$filteredResults1)
        <h5>I-1 – Paints and coating systems containing materials listed in table A and table B of appendix 1 of these guidelines</h4>

            <table  style="page-break-inside:avoid">
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
                    @if(count($filteredResults1) == 0)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    @else
                    @foreach($filteredResults1 as $value)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{ $value->check->equipment ?? '' }}</td>
                        <td>{{ $value->check->component ?? '' }}</td>
                        <td>{{ $value->check->location ?? '' }}@if($value->check->sub_location)
                            {{ ',' .$value->check->sub_location}}
                            @endif
                        </td>
                        <td>{{ $value->hazmat->name ?? '' }}</td>
                        <td>{{ $value->total }}</td>
                        <td>{{ $value->unit }}</td>
                        <td>{{ $value->lab_remarks }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            @endif
            @if(@$filteredResults2)
            <div style="padding-top: 20px;">
                <h4>I-2 Equipment and machinery containing materials listed in table A and table B of appendix 1 of these guidelines</h4>

                <table  style="page-break-inside:avoid">
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
                        @if(count($filteredResults2) == 0)
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        @else
                        @foreach($filteredResults2 as $value)
                        <tr>
                            <td>{{ $loop->iteration}}</td>
                            <td>{{ $value->check->equipment ?? '' }}</td>
                            <td>{{ $value->check->component ?? '' }}</td>
                            <td>{{ $value->check->location ?? '' }}@if($value->check->sub_location)
                                {{ ',' .$value->check->sub_location}}
                                @endif
                            </td>
                            <td>{{ $value->hazmat->name ?? '' }}</td>
                            <td>{{ $value->total }}</td>
                            <td>{{ $value->unit }}</td>
                            <td>{{ $value->lab_remarks }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @endif
            @if(@$filteredResults3)

            <div style="padding-top: 20px;">
                <h4>I-3 Structure and hull containing materials listed in table A and table B of appendix 1 of these guidelines</h4>

                <table  style="page-break-inside:avoid">
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
                        @if(count($filteredResults3) == 0)
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        @else
                        @foreach($filteredResults3 as $value)
                        <tr>
                            <td>{{ $loop->iteration}}</td>
                            <td>{{ $value->check->equipment ?? '' }}</td>
                            <td>{{ $value->check->component ?? '' }}</td>
                            <td>{{ $value->check->location ?? '' }}@if($value->check->sub_location)
                                {{ ',' .$value->check->sub_location}}
                                @endif
                            </td>
                            <td>{{ $value->hazmat->name ?? '' }}</td>
                            <td>{{ $value->total }}</td>
                            <td>{{ $value->unit }}</td>
                            <td>{{ $value->lab_remarks }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @endif



    </div>


    <!-- Section 1.2 -->


</div>
