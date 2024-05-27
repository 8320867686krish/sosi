<div  class="next">
    <div style="text-align: center;">
        <h1 style="padding-top: 30px;">{{$projectDetail['ihm_table']}} Report</h1><br />
        <h2 style="padding-top: 1px;">Report No: {{$projectDetail['project_no']}}</h2>
        <h3 style="padding-top: 1px;">SHIP NAME- {{$projectDetail['ship_name']}}</h3>
        <h3 style="padding-top: 1px;">IMO No: {{$projectDetail['imo_number']}}</h3>
        @php
        $imageData = $projectDetail['image'];
      
        @endphp
        <div style="padding-top: 30px;">
            <img src="{{ $imageData }}" alt="Your Image">
        </div>
    </div>
    <div style="text-align: right;">
        <p>Revision: 1</p>
        <p>Prepared & Internally Audited by SOS INDIA Pvt. Ltd.</p>
    </div>
    <div style="pading-top:20px">
        <h3>According to:</h3>
        <ul>
            <li>Hong Kong International Convention for the Safe and Environmentally Sound Recycling of
                Ships (SR/CONF 45)</li>
            <li>Hong Kong International Convention for the Safe and Environmentally Sound Recycling of
                Ships (SR/CONF 45)</li>
        </ul>
    </div>
</div>