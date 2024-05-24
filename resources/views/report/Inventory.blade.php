
 
    <div class="container page">
        <!-- Section 1.1 -->
        <h2>2. Inventory of Hazardous Materials (IHM) Part-1 </h2>
        <div class="section-1-1">
        <center> <h5>
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
