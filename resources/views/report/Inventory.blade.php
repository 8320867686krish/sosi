<div class="container">
    <!-- Section 1.1 -->
    <h2>2. Inventory of Hazardous Materials (IHM) Part-1 </h2>
    <div class="section-1-1">
        <center>
            <h5>
                Part-1
            </h5>
        </center>
        <center>
            <h4>Hazardous materials contained in the ship's structure and equipment</h4>
        </center>
        @if(@$filteredResults1)
        <h5>I-1 – Paints and coating systems containing materials listed in table A and table B of appendix 1 of these guidelines</h4>

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
<div class="container">
    <h3>Location Diagram of Contained HazMat & PCHM</h3>
    @php
    $html = '';
    foreach ($decks as $deck) {
                $html .= '<div class="outfit" style="position: relative; margin-bottom: 20px;">';
                $html .= '<img src="' . $deck['image'] . '" style="display: block;">';
                $html .= '<div id="showDeckCheck" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">';
            
                if (!empty($deck['checks'])) {
                    foreach ($deck['checks'] as $key => $value) {
                        $top = $value->position_top - ($value->isApp == 1 ? 20 : 0);
                        $left = $value->position_left - ($value->isApp == 1 ? 20 : 0);

                        $html .= '<div class="dot" style="position: absolute; top: ' . $top . 'px; left: ' . $left . 'px; width: 20px; height: 20px; border: 2px solid red; border-radius: 50%; text-align: center; line-height: 20px;">';
                        $html .= $key + 1;
                        $html .= '</div>';
                    }
                }
            
                $html .= '</div>';
                $html .= '</div>';
            }
            echo $html;
        @endphp
</div>