<div class="container">
    <!-- Section 1.1 -->
    <h2>1.0 Report</h2>
    <div class="section-1-1">
        <h3>1.1 Scope</h3>
        <p>This analysis of the Initial IHM (Part-1) report provided by the owner to bridge gaps with requirements laid
            out in HKC- MEPC.269(68) & EUSRR IHM requirements as per EMSA guidelines, and/or other limitations and
            challenges associated while making the Inital IHM reports & Additional samplings.</p>

    </div>

    <div class="section-1-1">
        <h3>1.2 Events</h3>
        <p>Upon the request from the recycling yard YS Investments, Solution for Overall Ship India Pvt. Ltd. (herein
            after referring as SOS India) visited the vessel for preparation of Gap analysis (herein after referring as
            GA) for the previous Inventory of Hazardous Material (IHM) provided by Vessel Owner.</p>
        <p>The followings were the salient points for preparation:</p>
        <ul>
            <li>Discussed the scope of the Gap analysis.</li>
            <li>Collected & assessed all the available information & Documents required for the GA.</li>
            <li>Possible gaps were identified while analysing existing IHM reports & additional checkpoints were
                finalized.</li>
            <li>Prepared VSCP (Visual sample checkpoints) taking references from existing IHM report, MEPC 269(68)
                guidelines, and EU guidelines (EMSA's Best Practice Guidance on the Inventory of Hazardous Materials)
                along with available vessel documents & various other technical reports & analysis.</li>
            <li>The onboard inspection was carried out, ship recycling yard input, while inspection, considered for
                additional samplings & risk assessment (See Appendix 3) carried out. Samples were collected with
                sampling precautions (See under Sampling Process and Survey Onboard), and sample points were sealed &
                marked to minimize possibilities of any leakage or scatterings, or contamination.</li>
            <li>Samples were dispatched to the relevant laboratory for lab testing owing to standards & specifications
                mentioned in MEPC 269(68) guidelines. (See the “Threshold values and test standard” in the cumulative
                list under Section A (applicable requirement))</li>
            <li>Prepared the Gap Analysis report after receiving the lab test results.(Attached are Lab reports in
                Appendix 2).</li>
        </ul>
        <p>The methods and procedures for developing GA report for the IHM of the vessel comply with the IMO Hong Kong
            International Convention for the Safe and Environmentally Sound Recycling of Ships (2009), considering the
            updated IHM Guidelines (MEPC.269(68)) developed by IMO and EU Ship Recycling Regulation (EU SRR) and EMSA’s
            Best Practice Guidance on the Inventory of Hazardous Materials.</p>
        <p>SOS India is using the work procedures stipulated in the Guidelines of the Convention for developing IHM on
            existing vessels.</p>
        <p>This includes the following:</p>
        <ul>
            <li>Collecting and assessing the vessel information including drawings, plans, specifications, etc.</li>
            <li>Preparation of Visual/Sampling Check Plan (VSCP) & Risk Assessment</li>
            <li>Onboard visual/sampling check with sampling precautions</li>
            <li>Testing whether the vessel contains any Hazardous Materials (HazMat) listed in the Hong Kong Convention
                and the IHM Guidelines (Appendix 1, table A & B) and EU Ship Recycling Regulation (Annex I and II).</li>
        </ul>
        <p>The additional sampling for the IHM survey onboard Maersk Patras was carried out by the SOS India
            representative whilst the vessel was at YS Investments LLP, Plot–59, Alang, Gujarat, India</p>
    </div>

    <div class="section-1-1 next">
        <h2>2.0 Executive Summary</h2>
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

    <div class="section-1-1 next">
        <h2>3.0 Abbreviation and Normative Reference</h2>
        <h3>3.1 Abbreviation</h3>
        <ul>
            <li>IHM= Inventory of Hazardous Materials</li>
            <li>ACMs= Asbestos Containing Materials</li>
            <li>PCBs= Poly chlorinated biphenyls</li>
            <li>ODS= Ozone Depleting Substances</li>
            <li>PBBs= Poly Brominated Biphenyls</li>
            <li>PBDEs=Poly Brominated Diphenyl ethers</li>
            <li>PCNs=Polychlorinated naphthalene</li>
            <li>SCCPs= Short chain chlorinated paraffin</li>
            <li>PFOS= Perfluorooctane Sulfonic Acid</li>
            <li>HBCDD= Hexa Bromo Cyclododecane</li>
            <li>POP= Persistent Organic Pollutants</li>
            <li>PCHM= Potentially Containing Hazardous Materials</li>
            <li>VSCP= Visual/Sampling Check Plan</li>
            <li>ECR= Engine control room</li>
            <li>CCR= Cargo control room</li>
            <li>D/G= Diesel Engine</li>
            <li>L.O. = Lubricating oil</li>
            <li>N.D. = Not Detected</li>
        </ul>
        <h3>3.2 Normative Reference:</h3>
        <ul>
            <li>Hong Kong International Convention for the Safe and Environmentally Sound Recycling of Ships, 2009
                (SR/CONF/45)</li>
            <li>2015 Guidelines for the Development of the Inventory of Hazardous Materials (MEPC. 269(68))</li>
            <li>EU Regulation on Ship Recycling, Regulation (EU) No1257/2013</li>
            <li>EMSA’s Best Practice Guidance on the Inventory of Hazardous Material, 2016-10-28</li>
            <li>SOLAS regulation II -1/3-5 new amendments concerning the new installation of asbestos-containing
                material, MSC.282(86)</li>
            <li>MSC. 1/Circ. 1426 Unified Interpretation of SOLAS Regulation II- 1/3-5</li>
            <li>MSC. 1/Circ.1374 Information on Prohibiting the use of asbestos onboard ships</li>
            <li>MSC. 1/Circ.1379 Unified Interpretation of SOLAS Regulation II- 1/3-5</li>
        </ul>
    </div>
    <div class="section-1-1 next">
        <h2>4.0 Section A: Applicable requirements</h2>
        <h3>4.1 Introduction</h3>
        <p>Development and preparation of Inventory of hazardous material as per MEPC 269(68) and EUSRR 1257/2013.</p>
        <h3>4.2 Objective</h3>
        <p>The objectives of the inventory are to provide ship-specific information on the actual
            hazardous materials onboard, to protect health and safety, and prevent environmental
            pollution at ship recycling facilities. This information will be used by the ship recycling
            facilities to decide how to manage the type and amounts of materials identified in the
            Inventory of Hazardous Material (IHM) (regulation 9 of the Hong Kong Convention).</p>
        <h3>4.3 Terminology</h3>
        <p>Convention: Hong Kong Convention</p>
        <p>Guideline: 2015 Guidelines for the development of the Inventory of Hazardous Material (IHM) i.e., MEPC
            269(68)</p>
        <p>Fixed: The condition that equipment or materials are securely fitted with the ship, such as by welding or
            with bolts, riveted or cemented, and used in their position, including electrical cables and gaskets.</p>
        <p>Homogeneous material: A material of uniform composition throughout that cannot be mechanically disjointed
            into different materials. In principle, the material cannot be separated by mechanical actions such as
            unscrewing, cutting, crushing, grinding, and abrasive processes.</p>
        <p>Loosely fitted equipment: Equipment or materials present onboard the ship by conditions other than “fixed”,
            such as fire extinguishers, distress flares, and lifebuoys. Product: Machinery, equipment, materials, and
            applied coatings onboard a ship. Threshold value: the concentration value in homogenous material.</p>
        <h3>4.4 Requirement</h3>
        <p>The scope for development of the Inventory of hazardous material on board mainly covers and consists of Part
            I: Materials contained in ship structure or equipment; and material to be listed in the inventory as per
            MEPC 269(68) and EU1257/2013, which are mentioned in appendix 1 of the MEPC 269(68) and Annex I, Annex II of
            the EU 1257/2013). These items are further classified according to their properties. As per IMO regulation
            Table A, and table B detailed as per MEPC 269(68) correspond to part I of the Inventory.</p>
    </div>

    <div class="section-1-1 next">
        <p style="font-size: 18px;"><b><u>The below cumulative list is its threshold values and test standard to
                    follow:</u></b></p>
        <table>
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Test Items</th>
                    <th>Threshold</th>
                    <th>Main Test Standards</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="3">1.</td>
                    <td rowspan="3">Asbestos</td>
                    <td rowspan="3">0.10%</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the Inventory of Hazardous Materials.</td>
                </tr>
                <tr>

                    <td>NIOSH 9000:1994, asbestos, chrysotile by XRD</td>
                </tr>
                <tr>

                    <td>ISO 22262-1:2012 Air quality -Bulk materials -Part 1: Sampling & qualitative determination of
                        asbestos in commercial bulk materials..</td>
                </tr>

                <tr>
                    <td rowspan="2">2.</td>
                    <td rowspan="2">Polychlorinated Biphenyls (PCBs)*</td>
                    <td rowspan="2">50mg/Kg</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the IHM</td>
                </tr>
                <tr>

                    <td>EPA8082A Polychlorinated biphenyls (PCBs) by Gas Chromatography</td>
                </tr>

                <tr>
                    <td rowspan="2">3.</td>
                    <td rowspan="2">Ozone Depleting substances (ODS)</td>
                    <td rowspan="2">No threshold value</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the Inventory of Hazardous Materials.</td>
                </tr>
                <tr>

                    <td>EPA 8260C Volatile Organic Compounds by Gas chromatography/Mass Spectrometer (GC-MS)</td>
                </tr>

                <tr>
                    <td rowspan="6">4.</td>
                    <td rowspan="6">Anti-Fouling systems containing Organotin compounds as a biocide (TBT, TPT, TBTO)
                    </td>
                    <td rowspan="6">2500 mg Total TIN /Kg</td>
                    <td>The International Convention on control of the harmful anti-fouling system on ships, 2001(AFS
                        convention)</td>
                </tr>
                <tr>

                    <td>MEPC. 104(49) Guidelines of brief sampling of anti-fouling systems</td>
                </tr>
                <tr>

                    <td>MEPC. 379(80) 2023 Guidelines for the development of the IHM</td>
                </tr>
                <tr>

                    <td>ISO17353:2004 water quality determination of selected organotin compound gas chromatographic
                        method</td>
                </tr>
                <tr>

                    <td>ISO11885:2007 Water quality-determination of selected elements by inductively coupled plasma
                        optical emission spectrometry (ICP-OES)</td>
                </tr>
                <tr>

                    <td>GB/T 26085-2010 Test method & determination of total tin in anti-fouling paints of the ships.
                    </td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Cybutryne</td>
                    <td>1,000 mg/kg</td>
                    <td>As per resolution MEPC.356(78) (2022 Guidelines for brief sampling of anti-fouling systems on
                        ships), adopted on 10 June 2022, using GC-MS.</td>
                </tr>
                <tr>
                    <td rowspan="3">6.</td>
                    <td rowspan="3">Cadmium & Cadmium compounds</td>
                    <td rowspan="3">100mg/Kg</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the IHM</td>
                </tr>
                <tr>
                    <td>IEC62321-3-1:2013 Screening-Lead, Mercury, Cadmium, total chromium & total Bromine by X-Ray
                        fluorescence spectrometry</td>
                </tr>
                <tr>
                    <td>IEC62321-5:2013 Determination of certain substances in electro technical products- Part 5:
                        Cadmium Lead & chromium in polymers & electronics and cadmium & lead in metals by AAS, AFS,
                        ICP-OES, and ICP-MS</td>
                </tr>

                <tr>
                    <td rowspan="4">7.</td>
                    <td rowspan="4">Hexavalent Chromium & Hexavalent Chromium compounds</td>
                    <td rowspan="4">1000mg/Kg</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the Inventory of Hazardous Materials.</td>
                </tr>
                <tr>
                    <td>IEC62321-3-1:2013 Screening-Lead, Mercury, Cadmium, total chromium & total Bromine by X-Ray
                        fluorescence spectrometry</td>
                </tr>
                <tr>
                    <td>IEC62321-7-1:2015 Electro technical products- Determination of levels of six regulated
                        chromium-Presence of hexavalent chromium (Cr (IV)) in colour less and coloured
                        corrosion-protected coatings on metals colorimetric methods</td>
                </tr>
                <tr>
                    <td>IEC62321-7-2:2017 Determination of certain substances in electro-chemical products- part 7-2:
                        hexavalent chromium-determination of hexavalent chromium in polymers and electronics by
                        colorimetric method</td>
                </tr>

                <tr>
                    <td rowspan="3">8.</td>
                    <td rowspan="3">Lead and Lead compounds</td>
                    <td rowspan="3">1000mg/Kg</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the Inventory of Hazardous Materials.</td>
                </tr>
                <tr>
                    <td>IEC62321-3-1:2013 Screening-Lead, Mercury, Cadmium, total chromium & total Bromine by X-Ray
                        fluorescence spectrometry</td>
                </tr>
                <tr>
                    <td>IEC62321-5:2013 Determination of certain substances in electro technical products-Part 5:
                        Cadmium, Lead & chromium in polymers & electronics and cadmium & lead in metals by AAS, AFS,
                        ICP-OES, and ICP-MS</td>
                </tr>


                <tr>
                    <td rowspan="3">9.</td>
                    <td rowspan="3">Mercury and Mercury compounds</td>
                    <td rowspan="3">1000mg/Kg</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the Inventory of Hazardous Materials.</td>
                </tr>
                <tr>
                    <td>IEC62321-3-1:2013 Screening-Lead, Mercury, Cadmium, total chromium & total Bromine by X-Ray
                        fluorescence spectrometry</td>
                </tr>
                <tr>
                    <td>IEC62321-4:2013 Determination of certain substances in electro technical products-part-4:
                        Mercury in polymers, metals & electronics by CV-AAS, CV-AFS, ICP-OES, and ICP-MS</td>
                </tr>




                <tr>
                    <td rowspan="3">10.</td>
                    <td rowspan="3">Per Fluoro Octane Sulfonic acid (PFOS) & its derivatives**</td>
                    <td rowspan="3">10mg/Kg</td>
                    <td>Per Fluoro Octane Sulfonic acid (PFOS) & its derivatives**.</td>
                </tr>
                <tr>
                    <td>ISO 25101 water Quality –Determination of Per Flouro Octane Sulfonic acid (PFOS) & Per Fluoro
                        Octanoate (PFOA) - Methods of unfiltered samples using solid-phase extraction & liquid
                        chromatography/Mass spectrometry</td>
                </tr>
                <tr>
                    <td>ICEN/TS15968 Determination of extractable Perflorooctane sulfonic acid (PFOS)in coat&
                        impregnated solid articles, liquids& firefighting foam. Methods of sampling, extraction &
                        analysis by LC-q M SorLC-tandem/MS</td>
                </tr>

                <tr>
                    <td rowspan="3">11.</td>
                    <td rowspan="3">Poly Brominated Biphenyl (PBBs)*</td>
                    <td rowspan="3">50mg/Kg</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the Inventory of Hazardous Materials.</td>
                </tr>
                <tr>
                    <td>IEC62321-3-1:2013 Screening-Lead, Mercury, Cadmium, total chromium & total Bromine by X-Ray
                        fluorescence spectrometry</td>
                </tr>
                <tr>
                    <td>IEC62321-6:2015 Determination of certain substances in electro technical products- part-6: Poly
                        Brominated biphenyl and Poly Brominated Diphenyl Ethers in polymers by gas chromatography-mass
                        spectrometry</td>
                </tr>

                <tr>
                    <td rowspan="3">12.</td>
                    <td rowspan="3">Poly Brominated Diphenyl Ethers (PBDEs)*</td>
                    <td rowspan="3">1000mg/Kg</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the Inventory of Hazardous Materials.</td>
                </tr>
                <tr>
                    <td>IEC62321-3-1:2013 Screening-Lead, Mercury, Cadmium, total chromium & total Bromine by X-Ray
                        fluorescence spectrometry</td>
                </tr>
                <tr>
                    <td>IEC62321-6:2015 Determination of certain substances in electro technical products- part-6: Poly
                        Brominated biphenyl and Poly Brominated Diphenyl Ethers in polymers by gas chromatography-mass
                        spectrometry</td>
                </tr>

                <tr>
                    <td rowspan="2">13.</td>
                    <td rowspan="2">Polychlorinated Naphthalenes (>3clatoms) (PCNs)*</td>
                    <td rowspan="2">50mg/Kg</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the Inventory of Hazardous Materials.</td>
                </tr>
                <tr>
                    <td>EPA8270D Semi volatile Organic Compounds by Gas chromatography/Mass Spectrometer (GC-MS)</td>
                </tr>

                <tr>
                    <td rowspan="2">14.</td>
                    <td rowspan="2">Certain short-chain chlorinated paraffin (Alkanes, C10-C13, chloro) (SCCP)*</td>
                    <td rowspan="2">1%</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the Inventory of Hazardous Materials.</td>
                </tr>
                <tr>
                    <td>EPA8270D Semi volatile Organic Compounds by Gas chromatography/Mass Spectrometer (GC-MS)</td>
                </tr>

                <tr>
                    <td rowspan="2">15.</td>
                    <td rowspan="2">Brominated flame retardant (HBCDD)**</td>
                    <td rowspan="2">100mg/Kg</td>
                    <td>Regulation (EU)No.1257/2013</td>
                </tr>
                <tr>
                    <td>EPA8270D Semi volatile Organic Compounds by Gas chromatography/Mass Spectrometer (GC-MS)</td>
                </tr>

                <tr>
                    <td rowspan="3">16.</td>
                    <td rowspan="3">Radioactive substances</td>
                    <td rowspan="3">NO Threshold value</td>
                    <td>MEPC. 379(80) 2023 Guidelines for the development of the Inventory of Hazardous Materials.</td>
                </tr>
                <tr>
                    <td>Radiation protection and safety of radiation sources: International Basic Safety Standards
                        (INTERIMEDITION)-General Safety Requirements Part-3</td>
                </tr>
                <tr>
                    <td>GB18871-2002 Basic Standards for protection against ionizing radiation and the safety of
                        radiation sources</td>
                </tr>

            </tbody>
        </table>
        <p>*Persistent organic compounds (POPs) which come under the purview of IHM are: Polychlorinated Naphthalenes
            (PCN), Polychlorinated biphenyls (PCB), Hexa Bromo Cyclodo Decane (HBCDD), Certain short-chain chlorinated
            paraffin (Alkanes, C10-C13, chloro) (SCCP), Poly Brominated Diphenyl Ethers (PBDEs), Poly Brominated
            Biphenyl (PBBs), Per Fluoro octane Sulfonic acid (PFOS) & its derivatives (PFOS)</p>
        <p>**HBCDD & PFOS are listed in EUSRR, but not listed in HKC.</p>
    </div>

    {{-- 4.0 Section A: Applicable requirements start --}}
    <div class="section-1-1 next">
        <h3>4.5 Development of Gap Analysis</h3>
        <p>To achieve comparable results for existing ship concerning part I of the Inventory, the following procedure
            shall be followed:</p>
        <ul>
            <li>Collection of the necessary information,</li>
            <li>Assessment of collected information,</li>
            <li>Preparation of visual/sampling check plan,</li>
            <li>Onboard visual check and sampling check,</li>
            <li>Preparation of report and related documentation</li>
        </ul>
        <ul>
            <li><u>Collection of necessary information</u>- It includes the collection of data from the Material
                Declaration Sheet (If available) provided by the ship-building yard, maintenance, conversion and repair
                documents, ship certificate, material certificate, component certificate, ship plan, drawing, and
                technical manual/specification, product information data sheets.</li>
            <li><u>Assessment of collected information</u>- The information collected in step 1 is assessed to cover the
                material listed in tables A and B of IMO regulation or Annex I and Annex II of EU regulation, which
                should be reflected in the visual/sampling check plan if the material/equipment/system is missed in the
                existing IHM.</li>

            <li><u>Preparation of visual/sampling check plan</u> - Visual/sampling check plan should be prepared
                considering the collected information and any relevant analysis. The visual/sampling check plan shall be
                prepared based on the following:
                <ul>
                    <li>Visual check when any equipment, system, and/or area specified regarding the presence of the
                        hazardous materials established by document available onboard.</li>
                    <li>Sampling check where the list of equipment, system, and/or area cannot be specified by document
                        or visual check and missed in the previous IHM report due to working condition and trading
                        pattern of the vessel or any other reasons. A sampling check is carried out by taking samples to
                        identify the presence or absence of hazardous material contained in the equipment, systems,
                        and/or areas, further by suitable and generally accepted methods such as laboratory analysis.
                    </li>
                    <li>Potentially containing hazardous material where any equipment, system, and/or area which cannot
                        be specified regarding the presence of the materials by document analysis and a sampling check
                        is inaccessible or cannot justify the contents. It is recommended that the PCHM samples which
                        are not sampled should be treated as CHM and disposed of as per the established procedure by
                        SRF.</li>
                </ul>
            </li>

            <li><u>Onboard visual check and sampling check</u> -The onboard visual/sampling check was carried out
                following the visual/sampling check plan. When a sampling check is carried out, samples are taken, the
                sample points are marked on the ship's plan and the sample results are referenced. Materials of the same
                kind may be sampled in a representative manner. Such materials are to be checked to ensure that they are
                of the same kind. Any uncertainty regarding the presence of hazardous materials is clarified by a
                visual/sampling check. Checkpoints are documented in the ship's plan and may be supported by
                photographs.</li>
            <li><u>Preparation of GA for the Inventory and related documentation</u> - The collection of additional
                samples from the equipment, system, and/or area is recommended or not, will be based on the collected
                information from the documents and existing IHM report, accessibility, practical solution of disposal as
                per established procedure, and the effective correlation from the result of the sampling. The document
                will provide you with the Location, Equipment/System, Component, and Material for sampling, finding in
                the previous IHM, our comments, and lastly our Remark/Recommendation.</li>
        </ul>

        <h3>4.6 Exemption and Exclusions</h3>
        <p>According to MEPC 269(68), 2015 Guidelines for the development of the Inventory of Hazardous Materials
            following are the Exemption:</p>
        <p>Materials listed in Table B that are inherent in solid metals or metal alloys, such as steels, aluminum,
            brass, bronze, plating, and solders, provided they are used in general construction, such as hull,
            superstructure, pipes, or housing for equipment and machinery, are not required to be listed in the
            inventory.</p>
        <p>Plastics and e-waste: MARPOL already regulates the reduction of environmental damage adequately caused by
            E-Waste and plastics and all the e-waste is disposed of as per the established procedure.</p>
        <p>It should be noted that the survey was carried out after the beaching of the vessel. The documents for
            document analysis were limited. Some of the documents were too deteriorated to analyze.</p>

        <h3>4.7 Objective</h3>
        <p>The purpose of the onboard survey was to identify hazardous material present in the ship’s structure and
            equipment and prepare a gap analysis of inventory (hereafter referring to GA) which will be an addition to
            the existing IHM provided by the shipowner.</p>

        <h3>4.8 Scope of analysis:</h3>
        <p>The scope of this inventory includes objects or materials containing one of the substances mentioned above in
            Overview of Table A (as per MEPC 269(68) or Annex I of EU 1257/2013). Objects and materials containing one
            of the substances mentioned in Table B (as per MEPC 269(68) or Annex II of EU 1257/2013) will be included in
            this study of the inventory, as far as practically possible.</p>

        <h3>4.9 Methodology</h3>
        <ul>
            <li><u>Pre-work</u> - The pre-work is to prepare and plan for the On-board survey as mentioned in the IMO
                guideline MEPC 269(68) and EU1257/2013,
                consequently, a Visual and Sampling Check Plan (here after referring as
                VSCP) has been prepared to utilize collecting the vessel-specific
                documents and reviewing the same.</li>
            <li><u>The Survey On-board</u> - The onboard procedure to cover and included the following tasks:
                <ul>
                    <li>Detailed discussion of work procedure.</li>
                    <li>Visual inspection of accessible areas</li>
                    <li>Review of VSCP in line with the review of the onboard document archive.</li>
                    <li>Marking of locations for sample collection.</li>
                    <li>Prepare risk assessment for additional sampling.</li>
                    <li>Discuss the sampling procedures and associated hazards in case of exposure and Mitigation
                        measures.</li>
                    <li>Collection of samples from the marked areas.</li>
                    <li>Seal the sampled area to minimize scattering or leakage or contamination</li>
                </ul>
            </li>

            <li><u>Limitations</u> - The Gap Analysis of Maersk Patras is based upon written
                documentation provided by the owner, findings from the onboard
                survey, and inshore/onboard archive. This report states the additional
                sample requirement after considering the previous IHM of relevant
                substances and materials present onboard Maersk Patras at the time of
                inspection. However, not all the required documents were presented for
                document analysis and preparation of VSCP. The collection of samples
                and visual verification is carried out as per the Paragraph 2.2- indicative
                list laid in MEPC 269(68).</li>
            <li>Document analysis - Based on the provided information the ship was
                built in Germany in the year 1998. For preparation, review, and
                verification of the visual and sampling plan, the following available
                documents were referred to for ascertaining the presence of Hazardous
                materials.</li>
            <ul>
                <li>Ship’s Particular</li>
                <li>General arrangement plan</li>
                <li>Engine room arrangement plan</li>
                <li>Capacity plan</li>
                <li>Fire & Safety Equipment plan</li>
                <li>Anti-fouling system certificates</li>
                <li>Existing IHM report prepared by Van de poel, provided by the shipowner.</li>
            </ul>
        </ul>
    </div>
    {{-- 4.0 Section A: Applicable requirements end --}}

    {{-- 6.0 Section C: Sampling Plan and Survey Onboard start --}}
    <div class="section-1-1 next">
        <h2>6.0 Section C: Sampling Plan and Survey Onboard</h2>
        <h3>6.1 Visual/sampling plan:</h3>
        <p>The equipment, system, and area are marked as document methods or visual methods in the sampling plan on
            completion of document analysis and visual inspection. Items listed in the VSCP were arranged in sequences
            so that the onboard survey could be conducted as structured as possible.</p>
        <p>The plan has been developed based on the previous IHM Hazmat Report and subsequently, additional sampling has
            been carried out.</p>
        <h3>6.2. Sampling Process and Survey Onboard Strategy</h3>
        <p>Our experts are trained and certified as DNV-GL HazMat Experts. Adequate safety
            measures were taken to prevent the spreading of materials, cross-contamination,
            or the spreading of fibres. At all times these measures are designed to prevent
            exposure to hazardous materials of workers, our team members, and/or others
            onboard. The onboard sampling was carried out as per the visual and sampling plan,
            the equipment, system, and the area selected for sampling were marked in the ship's
            plan and photographs are recorded.
        </p>
        <h3>6.2.1 Safety Precautions & its aspects:</h3>
        <p>Safety precautions while IHM sampling is divided into 3 aspects:</p>
        <ul>
            <li>Precaution for own safety</li>
            <li>Precaution for others’ safety</li>
            <li>Precaution against contamination to Ship & environment.</li>
        </ul>
        <ol>
            <li><u>Precaution for Own Safety:</u> Inform the person in charge of the ship &
                establish communication with the responsible person. Make a risk
                assessment before initiating the task. Check for the atmosphere, especially in
                confined spaces. Be equipped with the proper tool which should include but
                not be limited to:
                <ul>
                    <li>Drilling machine with spare bits</li>
                    <li>Cutter with spare blades</li>
                    <li>Chisel</li>
                    <li>Cutting Pliers</li>
                    <li>Permanent marker</li>
                    <li>Hammer</li>
                    <li>Water spray</li>
                    <li>Scrapper</li>
                    <li>Hand brush</li>
                    <li>Screwdriver</li>
                    <li>Torch</li>
                    <li>Poly bags</li>
                    <li>Plastic bottles which can be sealed properly,</li>
                    <li>Scissors</li>
                    <li>Masking tape</li>
                </ul>
                <p>The use of breathing apparatus is required to reduce the scattering of hazardous
                    substances, especially asbestos. Check for proper lighting. Make a notice: MAN AT
                    WORK to avoid unintended closing of area. Be equipped with proper PPE. It should
                    include but not be limited to:</p>
                    <ul>
                        <li>Boiler suits, additionally disposable suits</li>
                        <li>Safety goggles</li>
                        <li>Safety shoes, additionally Safety Boots, if necessary, like entering the ballast tank)</li>
                        <li>Ear protection</li>
                        <li>Gloves, additionally rubber glove</li>
                        <li>Air mask</li>
                        <li>Safety helmet</li>
                        <li>Gas detector</li>
                        <li>Safety harness</li>
                    </ul>
                <p>Do not touch any sample with your bare hands. Change boiler suits, disposable suits,
                    and gloves, and take a proper shower.</p>
            </li>
            <li><u>Precaution for Others’ Safety:</u> Do not put other people at risk. Cordon off the
                area if required while sampling, especially for asbestos sampling. Inform
                people in the vicinity & advise safety precautions Check for the people before
                closing the space. Clean the area after work; make sure any spillage is
                removed. Seal the sample area to avoid scattering or spillage.</li>
            <li><u>Precaution against Contaminations of Ship & Environment:</u> Sampling
                precautions must be taken to avoid spillage and contamination of the
                surrounding spaces. While sampling indoors, avoid strong ventilation but
                vent afterward. While sampling lubricants and hydraulic oil take care that
                the system is not under pressure. Cover the space under the sampling area
                for easy cleaning afterwards Prepare the material against spreading before
                sampling: moisten solids before breaking or cutting, and use water, oil, or a
                polymer. Use drill- bags when drilling dry material Re-install the cover after
                sampling Cover open edges and holes with tape, mastic, or a polymeric
                coating agent on board, a ship the options to reduce spillage, debris and
                airborne dust are limited, due to the requirements of the operation. But
                sampling naturally doesn't cause the same level of contamination as
                dismantling or large-scale works, for which it is commonly known that safety
                precautions should be taken. All hazardous materials in coatings are strongly
                bound in the matrix or incorporated in such a low concentration that release
                while sampling is normally insignificant.</li>
        </ol>
        <h3>6.2.2 Safety general precautions against individual HazMat:</h3>
        <ol>
            <li>Safety precautions for Asbestos (Asbestos-containing materials) sampling: The precautions required when working with ACMs are relatively easy to follow but must be strictly adhered to minimize the risk of releasing dangerous quantities of asbestos fibers. Anyone who samples asbestoscontaining materials should have as much information as possible on the handling of asbestos before sampling and, at a minimum, should observe the following procedures:
                <ul>
                    <li>Carryout a full site audit first and before any work begins. Carry out a risk assessment and write a method statement to ensure that the risk is reduced to the minimum possible and that may cause any fiber release.</li>
                    <li>Make sure no one else is in the room, without proper precaution, when the sampling is done.</li>
                    <li>Provide all operators with appropriate disposable masks and overalls to dispose of after each shift.</li>
                    <li>Ensure that all operatives are informed that they are working with asbestos.</li>
                    <li>Segregate the working areas with warning signs.</li>
                    <li>Wear disposable gloves or wash your hands for sampling.</li>
                    <li>Shut down any heating or cooling systems to minimize the spread of any released fibres.</li>
                    <li>Do not disturb the material any more than is needed to take a small sample.</li>
                    <li>Place a plastic sheet on the floor below the area to be sampled.</li>
                    <li>Removed delicately into one piece. Avoid breaking or using power tools unless necessary.</li>
                    <li>Wet the material using a fine mist of water containing a few drops of detergent before taking the sample. The water/detergent mist will reduce the release of asbestos fibres.</li>
                    <li>Carefully cut a piece from the entire depth of the material using a small knife or another sharp object. Place the small piece into a clean container (a35-mm film canister, small glass or plastic vial, or highquality sealable plastic bag).</li>
                    <li>Tightly seal the container after the sample is in it.</li>
                    <li>Carefully dispose of the plastic sheet. Use a damp paper towel to clean up any material on the outside of the container or around the area sampled. Dispose of asbestos materials according to state and local procedures.</li>
                    <li>Label the container with an identification number and clearly state when and where the sample was taken.</li>
                    <li>Patch the sampled area with the smallest possible piece of duct tape to prevent fibre release.</li>
                    <li>Always keep the site clean and tidy and cleanup after work by dampening any dust and carefully placing it in a polythene bag.</li>
                    <li>Send the sample to an asbestos analysis in an NABL-approved laboratory.</li>
                </ul>
            </li>
            <li><u>Safety Precautions for ODS Sampling:</u> Ozone-depleting substances cause no
                direct, but grave indirect harm to mankind; they can reduce the ozone layer and
                have a climate effect larger than that of carbon dioxide. Care should be taken to
                avoid the un-intended release of ODS into the atmosphere.</li>
            <li><u>Safety precautions for PCB sampling:</u> People handling PCBs or people that can
                be potentially exposed to PCBs have to use adequate protective equipment.
                The level of protection and the choice of protective equipment depend highly
                on the tasks carried out.
                <p>Example: A sampling of Liquid PCB:</p>
                <ul>
                    <li>Gloves Vinyl or latex (oil resistant)</li>
                    <li>Light respiratory Mask</li>
                </ul>
                <p>A sampling of capacitor containing PCB:</p>
                <ul>
                    <li>Gloves Vinyl or latex</li>
                    <li>Light respiratory Mask</li>
                    <li>Safety goggles only while opening or drilling.</li>
                </ul>
                <p>When taking samples of PCB or suspected material containing PCB, it must be worked tidily without losing or spreading sample material. Use an oilabsorbing carpet as a foundation if needed. Special attention is needed during the dismantling and packing of leaking PCB-containing capacitors. The main aim shall be to avoid cross-contamination. Therefore, immediately after the phase-out of the capacitors, the devices need to be placed in a drip tray. The surface should be cleaned and if necessary, a leakage stop device can be used. When packing capacitors an appropriate part of the area shall be covered with e.g., a chemical absorbing industrial carpet, an oil absorbent sheet, or other suitable materials, to protect it from cross-contamination or incidents during the packing procedure.</p>
            </li>
            <li>
                Safety precautions for POPs sampling: A sampling protocol is to be used and should contain the following information:
                <ul>
                    <li>Type of sample.</li>
                    <li>Location of sampling.</li>
                    <li>Any relevant information on the sample.</li>
                </ul>
                <p>The sample should be wrapped in aluminium foil and transferred into an airtight bag or container (e.g., glass or another inert material) with a cap or screw top. The vessel should be labelled (readable, persistent against solvents and water, with unique information e.g., code related to sampling protocol, if the sample represents any hazard this should be noted, and the sample labelled respectively). The collected samples should be stored adequately (e.g., appropriate temperature; possibly exclusion of light). Collected samples in sealed containers. Keep the sample in a dedicated/separate storage area with proper labelling.</p>
            </li>
            <li>
                Precautions for Heavy Metal Sampling: The following measures should be taken by researchers working with toxic metals:
                <ul>
                    <li>Read the safety data sheet (SDS) for each toxic metal or metal compound before use.</li>
                    <li>Eliminate, and substitute fewer toxic chemicals or reduce the quantities of toxic metals being used if possible.</li>
                    <li>Work with toxic metals in a chemical fume hood, glove box, or with other types of local exhaust ventilation.</li>
                    <li>Wear personal protective equipment as indicated by safety data sheets.</li>
                    <li>Ensure containers are clearly labelled and inspect containers for leaks or damage before use.</li>
                    <li>Store toxic metals in tightly sealed containers away from incompatible materials.</li>
                    <li>Corrosive, toxic metals (e.g., mercury) should be stored below eye level.</li>
                    <li>Do not return contaminated or unused material to the original container.</li>
                    <li>Ensure that emergency eye wash/shower stations are readily available.</li>
                    <li>Ensure that all waste containers are compatible with toxic metals and that the containers are properly labelled and stored.</li>
                </ul>
            </li>
            <li>Packing of IHM Sample: Bulky material (e.g., paint, isolation, concrete) should be
                given into a plastic bag which is allowed to glue a label on it. Maybe the bags are
                pre-labelled (label with sample no. on it). Liquid samples should be put into a
                coloured glass (up to 10ml is enough) and the glass should be put into a plastic
                containment that is matching (not too big) Breakable containments (glass)
                should be packed properly. In case the package is damaged because of not proper
                packing, Courier accepts no responsibility all samples are packed into one or
                more parcels which can then be sent to the laboratory. Samples should be
                labelled & marked. The location of samples should also be marked with their
                respective numbering.</li>
        </ol>
        <h3>7.2.3 Sampling Strategy</h3>
        <table>
            <thead>
                <tr>
                    <th align="center">Product Type</th>
                    <th align="center">Strategy</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Vinyl, composite floor covering, surface covering</td>
                    <td>One sample per room, or one sample per 40m2per product type or colour.</td>
                </tr>
                <tr>
                    <td>Texture Coating</td>
                    <td>Texture Coating One composite sample per room or one sample per 9m2 dependent on the similarity of coating type. Where large expanses of the sample have been used throughout an area. </td>
                </tr>
                <tr>
                    <td>Gasket, ropes, woven products,seals, mastics, Papers felt</td>
                    <td>One sample per product type. For gasket samples to be collected from individual systems.</td>
                </tr>
                <tr>
                    <td>Cement Product</td>
                    <td>One sample per product type, or if appropriate, per area or location. The specific nature of the material has been determined on-site using the competence and experience of the surveyor.</td>
                </tr>
                <tr>
                    <td>Paint</td>
                    <td>One sample per product type or if appropriate, per area or location. The sample area will be determined by the survey or through document analysis.</td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- 6.0 Section C: Sampling Plan and Survey Onboard end --}}

    {{-- 7.0 Risk Assessment in Gap Analysis and Sampling start --}}
    <div class="section-1-1 next">
        <h2>7.0 Risk Assessment in Gap Analysis and Sampling</h2>
        <h3>7.1 Law on risk assessment:</h3>
        <p>The law says that as an employer you must assess and control the risks in your workplace. You need to think about what might cause harm to people and decide whether you are doing enough to prevent that harm. If you have five or more employees, you must write down what you’ve found. That record should include:</p>
        <ul>
            <li>The hazards (things that may cause harm)</li>
            <li>How they may harm people</li>
            <li>What you are already doing to control the risks</li>
        </ul>
        <h3>8.2 Risk Assessment for IHM:</h3>
        <p>A good appreciation of HSE (health, safety&environment) risks in the area of responsibility helps to correctly direct resources for improvement. The Risk Assessment Matrix is a tool to rank and assess these risks and discuss what changes need to be made so that the risk is also was/is reasonably practicable.</p>
        <p>Ensuring that risk is managed in a structured and rational way is a necessary part of a safety management system. Rationalizing risk in this way can help better allocate resources and ensure that adequate barriers are put in place to prevent incidents. Applying the Risk Assessment Matrix properly and frequently is a natural way to communicate and plan for HSE improvement. The Risk Assessment Matrix can help managers better appreciate their roles in managing the HSE risk in their area of responsibility and can help them come to a good understanding of their role in demonstrating risk is managed too low as is reasonably practicable.
        </p>
        <p>How to use: Apply the Risk Assessment Matrix properly and frequently as a natural way to communicate and plan for HSE improvement.</p>
        <p>As a group exercise, consider the potential incidents that could happen and plot that incident on the Risk Assessment Matrix depending on its severity and the likelihood of happening. Consider how the risk of the incident happening can be reduced by changing how the work takes place or building extra safeguards.</p>
    </div>
    {{-- 7.0 Risk Assessment in Gap Analysis and Sampling end --}}
</div>
