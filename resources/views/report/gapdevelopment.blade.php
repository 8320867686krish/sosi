<!-- Section 1.1 -->
    <section id="section3.1">

        <h2>3.Development of IHM of({{$projectDetail['ship_name']}})</h2>
        <div class="section-1-1">
            <h3>3.1 Collection of Necessary Information</h3>
            <p>After receiving the request from the <b>{{$projectDetail['client']['name']}}</b> team SOSI requested and receive the following documents & plans from the ship :</p>
            <ul>
                @if(count($attechmentsResult)>0)
                @foreach($attechmentsResult as $value)
                <li>{{$loop->iteration}}. {{$value->heading}}</li>
                @endforeach
                @else
                <li>Not Found</li>
                @endif
            </ul>

        </div>
    </section>
    <section id="section3.2" class="section-1-1">
        <h3 style="padding-top:20px;">3.2 Analysis</h3>
        @php
            $json = json_decode(@$reportMaterials['extraField'],true);
          
        @endphp
        @if(@$json)
        @foreach($json as $key=>$value)
        <div style="margin-top:10px;">
           <h5><b>{{$key}}</b></h5>
           <p>{{$value}}</p>
        </div>
        @endforeach
        @endif

