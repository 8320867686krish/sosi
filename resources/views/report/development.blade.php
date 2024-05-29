<!-- Section 1.1 -->
<h2>3.Development of IHM of({{$projectDetail['ship_name']}})</h2>
<div class="section-1-1">
    <h3>3.1 Collection of Necessary Information</h3>
    <p>After receiving the request from the (client name) team SOSI requested and receive the following documents & plans from the ship :</p>
    <ul>
        @if(count($attechments)>0)
        @foreach($attechments as $value)
        <li>{{$loop->iteration}}. {{$value->heading}}</li>
        @endforeach
        @else
        <li>Not Found</li>
        @endif
    </ul>


    <h3 style="padding-top:20px;">3.2 Indicative List</h3>

    <h3>Materials to be listed from Table-A</h3>
    <p>Table A lists the following four materials</p>
    <ul>
        <li>1. Asbestos </li>
        <li>2. Polychlorinated biphenyls (PCBs) </li>
        <li>3. Ozone-depleting substances </li>
        <li>4. Anti-fouling systems containing organotin compounds as a biocide or</li>
        <li>5. Cybutryne</li>
        <li>6. PFOS (as pe EUSRR & EMSA)</li>
    </ul>
    <div style="margin-top:20px;">
        <h4><b>1.Asbestos</b></h4>
        <table>
            <thead>
                <tr>
                    <th>Structure and/or equipment</th>
                    <th>Component</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Propeller shafting</td>
                    <td>Packing with low pressure hydraulic piping flange</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model:</td>
                    <td>Packing with casing</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Manufacturer</td>
                    <td>Clutch</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Brake lining</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Synthetic stern tubes</td>
                </tr>






            </tbody>
        </table>
        <br /></br>
        <table>
            <thead>
                <tr>
                    <th>Structure and/or equipment</th>
                    <th>Component</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Diesel engine</td>
                    <td>Packing with piping flange</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model:</td>
                    <td>Lagging material for fuel pipe</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Manufacturer</td>
                    <td>Lagging material for fuel pipe</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Lagging material turbocharger</td>
                </tr>
                <tr>
                    <td style="border-bottom:none">Turbine engine</td>
                    <td>Urethane formed material</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Blowing agent for insulation of LNG carriers</td>
                </tr>
                <tr>
                    <td style="border-top:none"></td>
                    <td>Blowing agent for insulation of LNG carriers</td>
                </tr>




            </tbody>
        </table>
        <br /><br />

        <table>
            <thead>
                <tr>
                    <th>Structure and/or equipment</th>
                    <th>Component</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Boiler</td>
                    <td>Insulation in combustion chamber</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">#1</td>
                    <td>Packing for casing door</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model:</td>
                    <td>Lagging material for exhaust pipe</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Manufacturer:</td>
                    <td>Gasket for manhole</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">#2</td>
                    <td>Gasket for hand hole</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model:</td>
                    <td>Gas shield packing for soot blower and other holer</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Manufacturer</td>
                    <td>Packing with flange of piping and valve for steam line, exhaust line, fuel line and drain line</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Lagging material for piping and valve of steam line, exhaust line, fuel line and drain line</td>
                </tr>
                <tr>
                    <td style="border-bottom:none">Exhaust gas economizer</td>
                    <td>Packing for casing door</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Remarks</td>
                    <td>Packing with manhole</td>
                </tr>
                <tr>
                    <td style="border-top:none;border-bottom:none"></td>
                    <td>Packing with hand hole</td>
                </tr>
                <tr>
                    <td style="border-top:none;border-bottom:none"></td>
                    <td>Gas shield packing for soot blower</td>
                </tr>

                <tr>
                    <td style="border-bottom:none">Incinerator</td>
                    <td>Packing for casing door</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model:</td>
                    <td>Packing with manhole</td>
                </tr>
                <tr>
                    <td style="border-top:none;border-bottom:none">Manufacturer:</td>
                    <td>Packing with hand hole</td>
                </tr>
                <tr>
                    <td style="border-top:none"></td>
                    <td>Lagging material for exhaust pipe</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;">Auxiliary machinery (pump, compressor, oil purifier, crane)</td>
                    <td>Packing for casing door and valve</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Item: 1</td>
                    <td>Gland packing</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model:</td>
                    <td style="border-bottom:none;border-top:none;">Brake lining</td>
                </tr>
                <tr>
                    <td style="border-top:none;border-bottom:none">Manufacturer:</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Item: 2</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model:</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>
                <tr>
                    <td style="border-top:none;border-bottom:none">Manufacturer:</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model:</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>
                <tr>
                    <td style="border-top:none">Manufacturer</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>


                <tr>
                    <td style="border-bottom:none">Valve</td>
                    <td>Gland packing with valve, sheet packing with piping flange</td>
                </tr>
                <tr>
                    <td style="border-top:none;">Type:</td>
                    <td>Gland packing with valve, sheet packing with piping flange</td>
                </tr>
                <tr>
                    <td>Pipe, duct</td>
                    <td>Lagging material and insulation</td>
                </tr>
                <tr>
                    <td>Tank (fuel tank, hot water, tank, condenser), other equipment (fuel strainer, lubricant oil
                        strainer)</td>
                    <td>Lagging material and insulation</td>
                </tr>
                <tr>
                    <td>Electric equipment</td>
                    <td>Insulation material</td>
                </tr>
                <tr>
                    <td>Airborne asbestos</td>
                    <td>Wall, ceiling</td>
                </tr>
                <tr>
                    <td>Ceiling, floor and wall in accommodation area</td>
                    <td>Ceiling, floor, wall</td>
                </tr>
                <tr>
                    <td>Fire door</td>
                    <td>Packing, construction and insulation of the fire door</td>
                </tr>

                <tr>
                    <td style="border-bottom:none">Inert gas system</td>
                    <td style="border-bottom:none;border-top:none;">Packing for casing, etc.</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model::</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Manufacturer:</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>
                <tr>
                    <td style="border-bottom:none">Air conditioning system</td>
                    <td style="border-bottom:none;">Sheet packing, lagging material for piping and flexible joint</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model::</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Manufacturer:</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>




            </tbody>
        </table>
        <br /><br />
        <table>
            <thead>
                <tr>
                    <th>Structure and/or equipment</th>
                    <th>Component</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-bottom:none;">Miscellaneous</td>
                    <td>Ropes</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Thermal insulating materials</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Fire shields/fire proofing</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Space/duct insulation</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Electrical cable materials</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Brake linings</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Floor tiles/deck underlay</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Steam/water/vent flange gaskets</td>
                </tr>


                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Adhesives/mastics/fillers</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Sound damping</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Moulded plastic products</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Sealing putty</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Shaft/valve packing</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Electrical bulkhead penetration packing</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Circuit breaker arc chutes</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Pipe hanger inserts</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Weld shop protectors/burn covers</td>
                </tr>

                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td>Fire-fighting blankets/clothing/equipment</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;"></td>
                    <td> Concrete ballast</td>
                </tr>

            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;">
        <h4><b>2.Polychlorinated biphenyl (PCBs)</b></h4>
        <table>
            <thead>
                <tr>
                    <th>Equipment</th>
                    <th>Component of equipment</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td style="border-bottom:none;border-top:none;">Transformer</td>
                    <td style="border-bottom:none;border-top:none;">Insulating oil</td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Make/Model: </td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Type</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>
                <tr>
                    <td style="border-bottom:none;border-top:none;">Manufacturer:</td>
                    <td style="border-bottom:none;border-top:none;"></td>
                </tr>
                <tr>
                    <td>Condenser</td>
                    <td>Insulating oil</td>
                </tr>
                <tr>
                    <td>Fuel heater</td>
                    <td>Heating medium</td>
                </tr>

                <tr>
                    <td>Electric cable</td>
                    <td>Covering, insulating tape</td>
                </tr>

                <tr>
                    <td>Lubricating oil</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Heat oil</td>
                    <td>Thermometers, sensors, indicators</td>
                </tr>

                <tr>
                    <td>Rubber/felt gaskets</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Rubber hose</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Plastic foam insulation</td>
                    <td>&nbsp;</td>
                </tr>


                <tr>
                    <td>Thermal insulating materials</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Voltage regulators</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Voltage regulators</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Switches/reclosers/bushings</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Electromagnets</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Adhesives/tapes</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Surface contamination of machinerys</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Oil-based paint</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Caulking</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Rubber isolation mounts</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Pipe hangers</td>
                    <td>&nbsp;</td>
                </tr>



            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;">
        <h4><b>3.Ozone-depleting substances</b></h4>
        <table>
            <thead>
                <tr>
                    <th>Materials</th>
                    <th>Component of equipment</th>
                    <th>Period for use of ODS in Japan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CFCs (R11, R12)</td>
                    <td>Refrigerant for refrigerators</td>
                    <td>Until 1996</td>
                </tr>
                <tr>
                    <td rowspan="2">CFCs</td>


                    <td>Urethane formed material</td>
                    <td>Until 1996</td>

                <tr>
                    <td>Blowing agent for insulation of LNG carriers</td>
                    <td>Until 1996</td>
                </tr>
                <tr>
                    <td>Halons</td>
                    <td>Extinguishing agent</td>
                    <td>Until 1996</td>
                </tr>
                <tr>
                    <td>Other fully halogenated CFCs</td>
                    <td>The possibility of usage in ships is low</td>
                    <td>Until 1996</td>
                </tr>
                <tr>
                    <td>Carbon tetrachloride</td>
                    <td>The possibility of usage in ships is low</td>
                    <td>Until 1996</td>
                </tr>
                <tr>
                    <td>1,1,1-Trichloroethane (methyl chloroform)</td>
                    <td>The possibility of usage in ships is low</td>
                    <td>Until 1996</td>
                </tr>
                <tr>
                    <td>HCFC (R22, R141b)</td>
                    <td>Refrigerant for refrigerating machine</td>
                    <td>It is possible to use it until 2020</td>
                </tr>
                <tr>
                    <td>HBFC</td>
                    <td>The possibility of usage in ships is low</td>
                    <td>Until 1996</td>
                </tr>
                <tr>
                    <td>Methyl bromide</td>
                    <td>The possibility of usage in ships is low</td>
                    <td>Until 1996</td>
                </tr>






                </tr>
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;">
        <h4><b>4.Organotin compounds</b></h4>
        <p>Organotin compounds include tributyl tins (TBT), triphenyl tins (TPT) and tributyl tin oxide (TBTO). Organotin compounds have been used as anti-fouling paint on ships' bottoms, and the International Convention on the Control of Harmful Anti-fouling Systems on Ships (AFS Convention, as amended) stipulates that all ships shall not apply or reapply organotin compounds after 1 January 2003, and that, after 1 January 2008, all ships shall either not bear such compounds on their hulls or shall bear a coating that forms a barrier preventing such compounds from leaching into the sea. The above-mentioned dates may have been extended by permission of the Administration bearing in mind that the AFS Convention entered into force on 17 September 2008.</p>
    </div>
    <div style="margin-top:20px;">
        <h4><b>5.Cybutryne </b></h4>
        <p>Cybutryne has been used as biocide in anti-fouling systems, and the International Convention on the Control of Harmful Anti-fouling Systems on Ships (AFS Convention, as amended) stipulates that all ships shall not apply or reapply cybutryne after 1 January 2023, and that ships bearing an anti-fouling system that contains this substance in the external coating layer of their hulls or external parts or surfaces on 1 January 2023 shall either remove the anti-fouling system or apply a coating that forms a barrier to this substance leaching from the underlying non-compliant anti-fouling system at the next scheduled renewal of the anti-fouling system after 1 January 2023, but no later than 60 months following the last application to the ship of an anti-fouling system containing cybutryne.</p>
    </div>
    <div style="margin-top:20px;">
        <h4><b>6. Perfluoro octane sulfonic acid-PFOS (EUSRR/EMSA)</b></h4>
        <p>In the marine industry, it can be found in fire-fighting foams on vessels carrying inflammable
            fluids and those with helicopter decks, rubber, and plastic materials (i.e.: cable sheaths, PVC
            flooring, gaskets, and seals) and coatings (i.e. paint). An indicative list of materials and
        </p>
        <ul>
            <li>AFFF Foam Solution</li>
            <li>Coatings</li>
            <li>Adhesives</li>
        </ul>
    </div>
    <div style="margin-top:20px;">
        <h3>Materials listed from Table-B</h3>
        <table>
            <thead>
                <tr>
                    <th>Materials</th>
                    <th>Component of equipment</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Cadmium and cadmium compounds</td>
                    <td>Plating film, bearing</td>
                </tr>
                <tr>
                    <td>Hexavalent chromium compounds</td>
                    <td>Plating film</td>
                </tr>

                <tr>
                    <td>Mercury and mercury compounds</td>
                    <td>Fluorescent light, mercury lamp, mercury cell, liquid-level switch, gyro compass, thermometer, measuring tool, manganese cell, pressure sensors,
                        light fittings, electrical switches, fire detectors</td>
                </tr>
                <tr>
                    <td>Lead and lead compounds</td>
                    <td>Corrosion resistant primer, solder (almost all electric appliances contain solder), paints, preservative coatings, cable insulation, lead ballast, generators</td>
                </tr>
                <tr>
                    <td>Polybrominated biphenyls (PBBs)</td>
                    <td>Non-flammable plastics</td>
                </tr>
                <tr>
                    <td>Polybrominated diphenyl ethers (PBDE)</td>
                    <td>Non-flammable plastics</td>
                </tr>
                <tr>
                    <td>Polychlorinated naphthalenes</td>
                    <td>Paint, lubricating oil</td>
                </tr>
                <tr>
                    <td>Radioactive substances</td>
                    <td> <b>consumer products with radioactive materials</b>
                        <ul class="ulIteam">
                            <li> Ionization chamber smoke detectors (typical radionuclides 241Am; 226Ra)</li>
                            <li> Instruments/signs containing gaseous tritium light sources (3H)</li>
                            <li>Instruments/signs containing radioactive painting (typical radionuclide 226Ra)</li>
                            <li> High intensity discharge lamps (typical radionuclides 85Kr; 232Th)</li>
                            <li>Radioactive lighting rods (typical radionuclides 241Am; 226Ra)</li>
                        </ul>

                        <b>industrial gauges with radioactive materials</b>
                        <ul class="ulIteam">
                            <li>Radioactive level gauges</li>
                            <li>Radioactive dredger gauges</li>
                            <li>Radioactive conveyor gauges</li>
                            <li>Radioactive spinning pipe gauges</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Certain short-chain chlorinated paraffins</td>
                    <td>Non-flammable plastics</td>
                </tr>
                <tr>
                    <td>Brominated Flame Retardant (HBCDD)</td>
                    <td>
                        <b>An indicative list of materials and components that may contain HBCDD is the following:</b>
                        <ul class="ulIteam">
                            <li>A polymer made fire resistance insulation</li>
                            <li>Coatings</li>
                            <li>Flooring material</li>
                        </ul>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
    <div style="margin-top:20px;">
        <h3>3.3 Working Procedure </h3>
        <p>In order to create checks for the Visual Sampling Check Plan (VSCP) the following flow chart is referenced which is provided in MEPC 379(80) guidelines for preparation of the IHM.</p>


        <img src="https://sosindi.com/IHM/public/assets/images/procedure.jpg">
        <h4 style="font-weight: bold;text-align:center;padding-top:10px">Figure-1 Flowchart for IHM Checks</h4>
        <div style="text-align:justify;padding:10px">
            <p>1. Documents may include certificates, manuals, ship’s plans drawings, technical specifications and information from sister and/or similar ships.</p>

            <p>2. The assessment should cover all materials listed in table A of Appendix 1 of the Guideline; the control areas listed in table B should be assessed as practicable.<br />It is assumed not to be able to contain hazardous materials described above for areas assessed not equivalent listed in table 5 should be used as far as available.<br />Where possible additional documents related to knowledge gained during previous assessments may be used based on knowledge and logic.</p>

            <p>3. Equipment must be made clear which cannot and/or are area specific. It must be system and/or areas which cannot be specified shall be included in the record of the assessment.In addition, system scope of this assessment can become continuous sampling listed in Appendix 1 of these guidelines on a basis of the results just taken. In the conclusion, this document system incorporates documentation containing List required equipment/materials necessary to perform testing on the equipment. Each piece of equipment is an operational state efficiency at peak working hours each day before it is impossible to assess all equipment without compromising ship safety without operational efficiency.</p>

            <p>4. When assessing pieces that incorporate sampling identification badges/documents can also take into account age and tech state by comparing materials used in Appendix 1 List for classification systems with those currently present. In the prerequisite for this assessment method are described how best testing techniques are made more comprehensive by checking history records required hazardous material’s disposal documentation contained in appendix systems should also consider current “as is” position or any changes that have been made since last checked.</p>

            <p>5. When equipment, systems and/or area of ship are not accessible for visual check or sampling checks, this equipment system and/or area is classified as ‘Potentially Containing Hazardous Materials (PCHM)’</p>
        </div>
    </div>

    <div style="margin-top:20px;">
        <h3>3.4 VSCP Preparation</h3>
        Upon receiving the documents from the <b>{{$projectDetail['ship_name']}}</b>, team SOSI started the document analysis. A Visual Sampling check plan was prepared taking reference from the indicative list flowchart mentioned above in figure-1.

        <p>Following are the details of the checkpoints section wise:</p>
        @foreach($ChecksList as $value)
        
            @if(@$value['checks'])
            <div style="margin-top:20px">
            <p><b>Deck : {{$value['name']}}</b></p>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Location</th>
                        <th>Function, Equipment, Component</th>
                        <th>Materials</th>
                        <th>Document Analysis Result</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                @if(count($value['checks']) > 0)
                @foreach ($value->checks as $check)
                    @php $hazmatsCount = count($check->check_hazmats); @endphp
                    @if ($hazmatsCount == 0)
                    <tr>
                    <td>{{$check['name']}}</td>
                    <td>{{ $check->location }}   @if($check->sub_location){{ ',' . $check->sub_location }}
                    @endif</td>
                    <td>{{ $check->equipment }} , {{ $check->component }} </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>{{ $check->remarks }}</td>
                    </tr>
                    @endif
                    @foreach ($check->check_hazmats as $index => $hazmat)
                    <tr>
                    <td>{{$check['name']}}</td>
                    <td>{{ $check->location }}   @if($check->sub_location){{ ',' . $check->sub_location }}
                    @endif</td>
                    <td>{{ $check->equipment }} , {{ $check->component }} </td>
                    <td>{{ $hazmat->hazmat->short_name }}</td>
                    <td>{{ $hazmat->type }}</td>
                    <td>{{ $check->remarks }}</td>
                    </tr>
                    @endforeach
                @endforeach
                @else
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @endif
           
            @endif
            </table>
            </div>
            <div style="margin-top:20px">

          <img src="{{$value['image']}}">
            </div>

        @endforeach

    </div>

</div>