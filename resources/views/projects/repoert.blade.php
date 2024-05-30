<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
    <div class="accrodion-regular">
        <div id="accordion4">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            <span class="fas fa-angle-down mr-3"></span>Asbestos
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion4">
                    <div class="card-body">
                        @php
                            $minCount = 10;
                        @endphp
                        <form method="post" novalidate id="addReportMaterialForm">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}" id="project_id">
                            <input type="hidden" name="material_name" value="Asbestos">
                            <div class="row">
                                <div class="col-12">
                                    <h4>Propeller shafting</h4>
                                </div>
                                <div class="form-group col-12 mb-3">
                                    <label for="component_shafting">Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg" name="material[Propeller shafting][component][]" multiple data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing with low pressure hydraulic piping flange" {{ isset($foundItems['Propeller shafting']) && in_array("Packing with low pressure hydraulic piping flange", $foundItems['Propeller shafting']['component']) ? 'selected' : '' }}>Packing with low pressure hydraulic piping flange</option>
                                        <option value="Packing with casing" {{ isset($foundItems['Propeller shafting']) && in_array("Packing with casing", $foundItems['Propeller shafting']['component']) ? 'selected' : '' }}>Packing with casing</option>
                                        <option value="Clutch" {{ isset($foundItems['Propeller shafting']) && in_array("Clutch", $foundItems['Propeller shafting']['component']) ? 'selected' : '' }}>Clutch</option>
                                        <option value="Brake lining" {{ isset($foundItems['Propeller shafting']) && in_array("Brake lining", $foundItems['Propeller shafting']['component']) ? 'selected' : '' }}>Brake lining</option>
                                        <option value="Synthetic stern tubes" {{ isset($foundItems['Propeller shafting']) && in_array("Synthetic stern tubes", $foundItems['Propeller shafting']['component']) ? 'selected' : '' }}>Synthetic stern tubes</option>
                                    </select>
                                </div>

                                @php
                                    $shaftingIndex = 1;
                                    $shaftingMakes = isset($foundItems['Propeller shafting']['make']) && is_array($foundItems['Propeller shafting']['make']) ? $foundItems['Propeller shafting']['make'] : [];
                                @endphp

                                @foreach ($shaftingMakes as $shaftingMake)
                                    @if($shaftingIndex > $minCount)
                                        @break
                                    @endif

                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$shaftingIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Propeller shafting][model][]" autocomplete="off" {{ $readonly }} value="{{ $shaftingMake['model'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="manufacturer">Manufacturer {{$shaftingIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Propeller shafting][manufacturer][]" autocomplete="off" {{ $readonly }} value="{{ $shaftingMake['manufacturer'] ?? '' }}">
                                    </div>

                                    @php
                                        $shaftingIndex++;
                                    @endphp
                                @endforeach

                                @for($i = $shaftingIndex; $i <= $minCount; $i++)
                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Propeller shafting][model][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="manufacturer">Manufacturer {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Propeller shafting][manufacturer][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                @endfor
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Diesel engine</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg" name="material[Diesel engine][component][]" id="component_diesel" multiple data-live-search="true" data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing with piping flange" {{ isset($foundItems['Diesel engine']) && in_array("Packing with piping flange", $foundItems['Diesel engine']['component']) ? 'selected' : '' }}>Packing with piping flange</option>
                                        <option value="Lagging material for fuel pipe" {{ isset($foundItems['Diesel engine']) && in_array("Lagging material for fuel pipe", $foundItems['Diesel engine']['component']) ? 'selected' : '' }}>Lagging material for fuel pipe</option>
                                        <option value="Lagging material for exhaust pipe" {{ isset($foundItems['Diesel engine']) && in_array("Lagging material for exhaust pipe", $foundItems['Diesel engine']['component']) ? 'selected' : '' }}>Lagging material for exhaust pipe</option>
                                        <option value="Lagging material turbocharger" {{ isset($foundItems['Diesel engine']) && in_array("Lagging material turbocharger", $foundItems['Diesel engine']['component']) ? 'selected' : '' }}>Lagging material turbocharger</option>
                                    </select>
                                </div>

                                @php
                                    $engineIndex = 1;
                                    $engineMakes = isset($foundItems['Diesel engine']['make']) && is_array($foundItems['Diesel engine']['make']) ? $foundItems['Diesel engine']['make'] : [];
                                @endphp

                                @foreach ($engineMakes as $engineMake)
                                    @if($engineIndex > $minCount)
                                        @break
                                    @endif

                                    <div class="form-group col-12 col-lg-6">
                                        <label for="make">Make/Model {{$engineIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Diesel engine][model][]" autocomplete="off" {{ $readonly }} value="{{ $engineMake['model'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-12 col-lg-6">
                                        <label for="manufacturer">Manufacturer {{$engineIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Diesel engine][manufacturer][]" autocomplete="off" {{ $readonly }} value="{{ $engineMake['manufacturer'] ?? '' }}">
                                    </div>

                                    @php
                                        $engineIndex++;
                                    @endphp
                                @endforeach

                                @for($i = $engineIndex; $i <= $minCount; $i++)
                                    <div class="form-group col-12 col-lg-6">
                                        <label for="make">Make/Model {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Diesel engine][model][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                    <div class="form-group col-12 col-lg-6">
                                        <label for="manufacturer">Manufacturer {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Diesel engine][manufacturer][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                @endfor
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Boiler</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg" name="material[Boiler][component][]" multiple data-live-search="true" data-actions-box="true" {{ $readonly }}>
                                        <option value="Insulation in combustion chamber" {{ isset($foundItems['Boiler']) && in_array("Insulation in combustion chamber", $foundItems['Boiler']['component']) ? 'selected' : '' }}>Insulation in combustion chamber</option>
                                        <option value="Packing for casing door" {{ isset($foundItems['Boiler']) && in_array("Packing for casing door", $foundItems['Boiler']['component']) ? 'selected' : '' }}>Packing for casing door</option>
                                        <option value="Lagging material for exhaust pipe" {{ isset($foundItems['Boiler']) && in_array("Lagging material for exhaust pipe", $foundItems['Boiler']['component']) ? 'selected' : '' }}>Lagging material for exhaust pipe</option>
                                        <option value="Gasket for manhole" {{ isset($foundItems['Boiler']) && in_array("Gasket for manhole", $foundItems['Boiler']['component']) ? 'selected' : '' }}>Gasket for manhole</option>
                                        <option value="Gasket for hand hole" {{ isset($foundItems['Boiler']) && in_array("Gasket for hand hole", $foundItems['Boiler']['component']) ? 'selected' : '' }}>Gasket for hand hole</option>
                                        <option value="Gas shield packing for soot blower and other hole" {{ isset($foundItems['Boiler']) && in_array("Gas shield packing for soot blower and other hole", $foundItems['Boiler']['component']) ? 'selected' : '' }}>Gas shield packing for soot blower and other hole</option>
                                        <option value="Packing with flange of piping and valve for steam line exhaust line fuel line and drain line" {{ isset($foundItems['Boiler']) && in_array("Packing with flange of piping and valve for steam line exhaust line fuel line and drain line", $foundItems['Boiler']['component']) ? 'selected' : '' }}>Packing with flange of piping and valve for steam line, exhaust line, fuel line and drain line</option>
                                        <option value="Lagging material for piping and valve of steam line exhaust line fuel line and drain line" {{ isset($foundItems['Boiler']) && in_array("Lagging material for piping and valve of steam line exhaust line fuel line and drain line", $foundItems['Boiler']['component']) ? 'selected' : '' }}>Lagging material for piping and valve of steam line, exhaust line, fuel line and drain line</option>
                                    </select>
                                </div>

                                @php
                                    $boilerIndex = 1;
                                    $boilerMakes = isset($foundItems['Boiler']['make']) && is_array($foundItems['Boiler']['make']) ? $foundItems['Boiler']['make'] : [];
                                @endphp

                                @foreach ($boilerMakes as $boilerMake)
                                    @if($boilerIndex > $minCount)
                                        @break
                                    @endif

                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$boilerIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Boiler][model][]" autocomplete="off" {{ $readonly }} value="{{ $boilerMake['model'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="">Manufacturer {{$boilerIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Boiler][manufacturer][]" autocomplete="off" {{ $readonly }} value="{{ $boilerMake['manufacturer'] ?? '' }}">
                                    </div>

                                    @php
                                        $boilerIndex++;
                                    @endphp
                                @endforeach

                                @for($i = $boilerIndex; $i <= $minCount; $i++)
                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Boiler][model][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="">Manufacturer {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Boiler][manufacturer][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                @endfor
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Exhaust gas economizer</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg" name="material[Exhaust gas economizer][component][]" multiple data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing for casing door" {{ isset($foundItems['Exhaust gas economizer']) && in_array("Packing for casing door", $foundItems['Exhaust gas economizer']['component']) ? 'selected' : '' }}>Packing for casing door</option>
                                        <option value="Packing with manhole" {{ isset($foundItems['Exhaust gas economizer']) && in_array("Packing with manhole", $foundItems['Exhaust gas economizer']['component']) ? 'selected' : '' }}>Packing with manhole</option>
                                        <option value="Packing with hand hole" {{ isset($foundItems['Exhaust gas economizer']) && in_array("Packing with hand hole", $foundItems['Exhaust gas economizer']['component']) ? 'selected' : '' }}>Packing with hand hole</option>
                                        <option value="Gas shield packing for soot blower" {{ isset($foundItems['Exhaust gas economizer']) && in_array("Gas shield packing for soot blower", $foundItems['Exhaust gas economizer']['component']) ? 'selected' : '' }}>Gas shield packing for soot blower</option>
                                        <option value="Packing with flange of piping and valve for steam line exhaust line fuel line and drain line" {{ isset($foundItems['Exhaust gas economizer']) && in_array("Packing with flange of piping and valve for steam line exhaust line fuel line and drain line", $foundItems['Exhaust gas economizer']['component']) ? 'selected' : '' }}>Packing with flange of piping and valve for steam line, exhaust line, fuel line and drain line</option>
                                        <option value="Lagging material for piping and valve of steam line exhaust line fuel line and drain line" {{ isset($foundItems['Exhaust gas economizer']) && in_array("Lagging material for piping and valve of steam line exhaust line fuel line and drain line", $foundItems['Exhaust gas economizer']['component']) ? 'selected' : '' }}>Lagging material for piping and valve of steam line, exhaust line, fuel line and drain line</option>
                                    </select>
                                </div>

                                <div class="form-group col-12 mb-4">
                                    <label for="make">Remark</label>
                                    <textarea name="material[Exhaust gas economizer][remark]" id="" rows="3" class="form-control" {{ $readonly }}>v{{$foundItems['Exhaust gas economizer']['remark'] ?? ''}}</textarea>
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Incinerator</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg" name="material[Incinerator][component][]" multiple data-live-search="true" data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing for casing door" {{ isset($foundItems['Incinerator']) && in_array("Packing for casing door", $foundItems['Incinerator']['component']) ? 'selected' : '' }}>Packing for casing door</option>
                                        <option value="Packing with manhole" {{ isset($foundItems['Incinerator']) && in_array("Packing with manhole", $foundItems['Incinerator']['component']) ? 'selected' : '' }}>Packing with manhole</option>
                                        <option value="Packing with hand hole" {{ isset($foundItems['Incinerator']) && in_array("Packing with hand hole", $foundItems['Incinerator']['component']) ? 'selected' : '' }}>Packing with hand hole</option>
                                        <option value="Lagging material for exhaust pipe" {{ isset($foundItems['Incinerator']) && in_array("Lagging material for exhaust pipe", $foundItems['Incinerator']['component']) ? 'selected' : '' }}>Lagging material for exhaust pipe</option>
                                        <option value="Lagging material for exhaust pipe" {{ isset($foundItems['Incinerator']) && in_array("Lagging material for exhaust pipe", $foundItems['Incinerator']['component']) ? 'selected' : '' }}>Lagging material for exhaust pipe</option>
                                    </select>
                                </div>

                                @php
                                    $incineratorIndex = 1;
                                    $incineratorMakes = isset($foundItems['Incinerator']['make']) && is_array($foundItems['Incinerator']['make']) ? $foundItems['Incinerator']['make'] : [];
                                @endphp

                                @foreach ($incineratorMakes as $incineratorMake)
                                    @if($incineratorIndex > $minCount)
                                        @break
                                    @endif

                                    <div class="form-group col-12 col-lg-6">
                                        <label>Make/Model {{$incineratorIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Incinerator][model][]" autocomplete="off" {{ $readonly }} value="{{ $incineratorMake['model'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-12 col-lg-6">
                                        <label>Manufacturer {{$incineratorIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Incinerator][manufacturer][]" autocomplete="off" {{ $readonly }} value="{{ $incineratorMake['manufacturer'] ?? '' }}">
                                    </div>

                                    @php
                                        $incineratorIndex++;
                                    @endphp
                                @endforeach

                                @for($i = $incineratorIndex; $i <= $minCount; $i++)
                                    <div class="form-group col-12 col-lg-6">
                                        <label>Make/Model {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Incinerator][model][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                    <div class="form-group col-12 col-lg-6">
                                        <label>Manufacturer {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Incinerator][manufacturer][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                @endfor
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Auxiliary machinery (pump, compressor, oil purifier, crane)</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg" name="material[Auxiliary machinery][component][]" multiple data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing for casing door and valve" {{ isset($foundItems['Auxiliary machinery']) && in_array("Packing for casing door and valve", $foundItems['Auxiliary machinery']['component']) ? 'selected' : '' }}>Packing for casing door and valve</option>
                                        <option value="Gland packing" {{ isset($foundItems['Auxiliary machinery']) && in_array("Gland packing", $foundItems['Auxiliary machinery']['component']) ? 'selected' : '' }}>Gland packing</option>
                                        <option value="Brake lining" {{ isset($foundItems['Auxiliary machinery']) && in_array("Brake lining", $foundItems['Auxiliary machinery']['component']) ? 'selected' : '' }}>Brake lining</option>
                                    </select>
                                </div>

                                @php
                                    $machineryIndex = 1;
                                    $machineryMakes = isset($foundItems['Auxiliary machinery']['make']) && is_array($foundItems['Auxiliary machinery']['make']) ? $foundItems['Auxiliary machinery']['make'] : [];

                                @endphp

                                @foreach ($machineryMakes as $machineryMake)
                                    @if($machineryIndex > $minCount)
                                        @break
                                    @endif

                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$machineryIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Auxiliary machinery][model][]" autocomplete="off" {{ $readonly }} value="{{ $machineryMake['model'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Manufacturer {{$machineryIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Auxiliary machinery][manufacturer][]" autocomplete="off" {{ $readonly }} value="{{ $machineryMake['manufacturer'] ?? '' }}">
                                    </div>

                                    @php
                                        $machineryIndex++;
                                    @endphp
                                @endforeach

                                @for($i = $machineryIndex; $i <= $minCount; $i++)
                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Auxiliary machinery][model][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Manufacturer {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Auxiliary machinery][manufacturer][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                @endfor
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Heat exchanger</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-6 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg" name="material[Heat exchanger][component][]" multiple data-live-search="true" data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing with casing" {{ isset($foundItems['Heat exchanger']) && in_array("Packing with casing", $foundItems['Heat exchanger']['component']) ? 'selected' : '' }}>Packing with casing</option>
                                        <option value="Gland packing for valve" {{ isset($foundItems['Heat exchanger']) && in_array("Gland packing for valve", $foundItems['Heat exchanger']['component']) ? 'selected' : '' }}>Gland packing for valve</option>
                                        <option value="Lagging material and insulation" {{ isset($foundItems['Heat exchanger']) && in_array("Lagging material and insulation", $foundItems['Heat exchanger']['component']) ? 'selected' : '' }}>Lagging material and insulation</option>
                                    </select>
                                </div>

                                <div class="form-group col-6">
                                    <label>Type</label>
                                    <input type="text" class="form-control form-control-lg" name="material[Heat exchanger][type]" autocomplete="off" {{ $readonly }} value="{{$foundItems['Heat exchanger']['type'] ?? ''}}">
                                </div>

                                @php
                                    $exchangerIndex = 1;
                                    $exchangerMakes = isset($foundItems['Heat exchanger']['make']) && is_array($foundItems['Heat exchanger']['make']) ? $foundItems['Heat exchanger']['make'] : [];
                                @endphp

                                @foreach ($exchangerMakes as $exchangerMake)
                                    @if($exchangerIndex > $minCount)
                                        @break
                                    @endif

                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$exchangerIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Heat exchanger][model][]" autocomplete="off" {{ $readonly }} value="{{ $exchangerMake['model'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-6 mb-4">
                                        <label for="manufacturer">Manufacturer {{$exchangerIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Heat exchanger][manufacturer][]" autocomplete="off" {{ $readonly }} value="{{ $exchangerMake['manufacturer'] ?? '' }}">
                                    </div>

                                    @php
                                        $exchangerIndex++;
                                    @endphp
                                @endforeach

                                @for($i = $exchangerIndex; $i <= $minCount; $i++)
                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Heat exchanger][model][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                    <div class="form-group col-6 mb-4">
                                        <label for="manufacturer">Manufacturer {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Heat exchanger][manufacturer][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                @endfor
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Inert gas system</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label for="component">Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg" name="material[Inert gas system][component][]" multiple data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing for casing etc." {{ isset($foundItems['Inert gas system']) && in_array("Packing for casing etc.", $foundItems['Inert gas system']['component']) ? 'selected' : '' }}>Packing for casing, etc.</option>
                                    </select>
                                </div>

                                @php
                                    $inertGasSystemIndex = 1;
                                    $inertGasSystemMakes = isset($foundItems['Inert gas system']['make']) && is_array($foundItems['Inert gas system']['make']) ? $foundItems['Inert gas system']['make'] : [];
                                @endphp

                                @foreach ($inertGasSystemMakes as $inertGasSystemMake)
                                    @if($inertGasSystemIndex > $minCount)
                                        @break
                                    @endif

                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$inertGasSystemIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Inert gas system][model][]" autocomplete="off" {{ $readonly }} value="{{ $inertGasSystemMake['model'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="manufacturer">Manufacturer {{$inertGasSystemIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Inert gas system][manufacturer][]" autocomplete="off" {{ $readonly }} value="{{ $inertGasSystemMake['manufacturer'] ?? '' }}">
                                    </div>

                                    @php
                                        $inertGasSystemIndex++;
                                    @endphp
                                @endforeach

                                @for($i = $inertGasSystemIndex; $i <= $minCount; $i++)
                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Inert gas system][model][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="manufacturer">Manufacturer {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Inert gas system][manufacturer][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                @endfor
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Air conditioning system</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg" name="material[Air conditioning system][component][]" multiple data-actions-box="true" {{ $readonly }}>
                                        <option value="Sheet packing lagging material for piping and flexible joint." {{ isset($foundItems['Air conditioning system']) && in_array("Sheet packing lagging material for piping and flexible joint.", $foundItems['Air conditioning system']['component']) ? 'selected' : '' }}>Sheet packing, lagging material for piping and flexible joint.</option>
                                    </select>
                                </div>

                                @php
                                    $acSystemIndex = 1;
                                    $acSystemMakes = isset($foundItems['Air conditioning system']['make']) && is_array($foundItems['Air conditioning system']['make']) ? $foundItems['Air conditioning system']['make'] : [];
                                @endphp

                                @foreach ($acSystemMakes as $acSystemMake)
                                    @if($acSystemIndex > $minCount)
                                        @break
                                    @endif

                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$acSystemIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Air conditioning system][model][]" autocomplete="off" {{ $readonly }} value="{{ $acSystemMake['model'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="manufacturer">Manufacturer {{$acSystemIndex}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Air conditioning system][manufacturer][]" autocomplete="off" {{ $readonly }} value="{{ $acSystemMake['manufacturer'] ?? '' }}">
                                    </div>

                                    @php
                                        $acSystemIndex++;
                                    @endphp
                                @endforeach

                                @for($i = $acSystemIndex; $i <= $minCount; $i++)
                                    <div class="form-group col-6">
                                        <label for="make">Make/Model {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Air conditioning system][model][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="manufacturer">Manufacturer {{$i}}</label>
                                        <input type="text" class="form-control form-control-lg" name="material[Air conditioning system][manufacturer][]" autocomplete="off" {{ $readonly }} value="">
                                    </div>
                                @endfor

                                @can('projects.edit')
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button class="btn btn-primary float-right" type="submit">Save</button>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <span class="fas fa-angle-down mr-3"></span>Polychlorinated biphenyl (PCBs)
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion4">
                    <div class="card-body">
                        <form method="post" id="PolychlorinatedForm">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}">
                            <input type="hidden" name="material_name" value="Polychlorinated biphenyl (PCBs)">
                            <h4>Transformer</h4>
                            <div class="row">
                                @php
                                    $extraFieldData = json_decode(@$foundItems['Transformer']['extraField'], true);
                                @endphp
                                <div class="form-group col-6 mb-3">
                                    <label for="component">Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg" name="material[Transformer][component][]" multiple data-actions-box="true" {{ $readonly }}>
                                        <option value="Insulating oil" {{ isset($foundItems['Transformer']) && in_array("Insulating oil", $foundItems['Transformer']['component']) ? 'selected' : '' }}>Insulating oil</option>
                                    </select>
                                </div>

                                <div class="form-group col-6">
                                    <label>Type</label>
                                    <input type="text" class="form-control form-control-lg" name="material[Transformer][type]" autocomplete="off" {{ $readonly }} value="{{$foundItems['Transformer']['type'] ?? ''}}">
                                </div>
                                <div class="form-group col-6">
                                    <label for="make">Make/Model</label>
                                    <input type="text" class="form-control form-control-lg" name="material[Transformer][model]" autocomplete="off" {{ $readonly }} value="{{$foundItems['Transformer']['make'][0]['model'] ?? ''}}">
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label>Manufacturer</label>
                                    <input type="text" class="form-control form-control-lg" name="material[Transformer][manufacturer]" autocomplete="off" {{ $readonly }} value="{{$foundItems['Transformer']['make'][0]['manufacturer'] ?? ''}}">
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3"></h4>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="rubberFelt">Rubber/felt gaskets</label>
                                        <input type="text" name="extraField[Rubber/felt gaskets]" id="rubberFelt" class="form-control form-control-lg" value="{{$extraFieldData['Rubber/felt gaskets'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="rubberHose">Rubber hose</label>
                                        <input type="text" name="extraField[Rubber hose]" id="rubberHose" class="form-control form-control-lg" value="{{$extraFieldData['Rubber hose'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="plasticFoam">Plastic foam insulation</label>
                                        <input type="text" name="extraField[Plastic foam insulation]" id="plasticFoam" class="form-control form-control-lg" value="{{$extraFieldData['Plastic foam insulation'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="thermal">Thermal insulating materials</label>
                                        <input type="text" name="extraField[Thermal insulating materials]" id="thermal" class="form-control form-control-lg" value="{{$extraFieldData['Thermal insulating materials'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="voltage">Voltage regulators</label>
                                        <input type="text" name="extraField[Voltage regulators]" id="voltage" class="form-control form-control-lg" value="{{$extraFieldData['Voltage regulators'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="switches">Switches/reclosers/bushings</label>
                                        <input type="text" name="extraField[Switches/reclosers/bushings]" id="switches" class="form-control form-control-lg" value="{{$extraFieldData['Switches/reclosers/bushings'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="electromagnets">Electromagnets</label>
                                        <input type="text" name="extraField[Electromagnets]" id="electromagnets" class="form-control form-control-lg" value="{{$extraFieldData['Electromagnets'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="adhesives">Adhesives/tapes</label>
                                        <input type="text" name="extraField[Adhesives/tapes]" id="adhesives" class="form-control form-control-lg" value="{{$extraFieldData['Adhesives/tapes'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="surfaceContamination">Surface contamination of machinery</label>
                                        <input type="text" name="extraField[Surface contamination of machinery]" id="adhesives" class="form-control form-control-lg" value="{{$extraFieldData['Surface contamination of machinery'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="oilBasedPaint">Oil-based paint</label>
                                        <input type="text" name="extraField[Oil-based paint]" id="oilBasedPaint" class="form-control form-control-lg" value="{{$extraFieldData['Oil-based paint'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="caulking">Caulking</label>
                                        <input type="text" name="extraField[Caulking]" id="caulking" class="form-control form-control-lg" value="{{$extraFieldData['Caulking'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="rubberIsolation">Rubber isolation mounts</label>
                                        <input type="text" name="extraField[Rubber isolation mounts]" id="rubberIsolation" class="form-control form-control-lg" value="{{$extraFieldData['Rubber isolation mounts'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="pipeHangers">Pipe hangers</label>
                                        <input type="text" name="extraField[Pipe hangers]" id="rubberIsolation" class="form-control form-control-lg" value="{{$extraFieldData['Pipe hangers'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="lightBallasts">Light ballasts (component within fluorescent light fixtures)</label>
                                        <input type="text" name="extraField[Light ballasts (component within fluorescent light fixtures)]" id="rubberIsolation" class="form-control form-control-lg" value="{{$extraFieldData['Light ballasts (component within fluorescent light fixtures)'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="plasticizers">Plasticizers</label>
                                        <input type="text" name="extraField[plasticizers]" id="plasticizers" class="form-control form-control-lg" value="{{$extraFieldData['plasticizers'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="feltUnderSeptum">Felt under septum plates on top of hull bottom</label>
                                        <input type="text" name="extraField[Felt under septum plates on top of hull bottom]" id="feltUnderSeptum" class="form-control form-control-lg" value="{{$extraFieldData['Felt under septum plates on top of hull bottom'] ?? ''}}">
                                    </div>
                                </div>
                                @can('projects.edit')
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button class="btn btn-primary float-right" type="submit">Save</button>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <span class="fas fa-angle-down mr-3"></span>Ozone-depleting substances
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion4">
                    <div class="card-body">
                        <form method="post" id="ozoneDepletingForm">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}">
                            <input type="hidden" name="material_name" value="Ozone depleting substances">
                            <h4>Refrigeration System</h4>
                            <div class="row">
                                @php
                                    $refrigerationData = json_decode(@$foundItems['Refrigeration System']['extraField'], true);
                                    $acData = json_decode(@$foundItems['Ac System']['extraField'], true);
                                @endphp
                                <div class="form-group col-6 mb-4">
                                    <label>Name</label>
                                    <input type="text" class="form-control form-control-lg" name="ozoneDepleting[Refrigeration System][name]" autocomplete="off" {{ $readonly }} value="{{$refrigerationData['name'] ?? ''}}">
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label>Make/Model/Manufacturer</label>
                                    <input type="text" class="form-control form-control-lg" name="ozoneDepleting[Refrigeration System][model]" autocomplete="off" {{ $readonly }} value="{{$refrigerationData['model'] ?? ''}}">
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Ac System</h4>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="ozoneDepleting[Ac System][name]" class="form-control form-control-lg" value="{{$acData['name'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label>Make/Model</label>
                                        <input type="text" name="ozoneDepleting[Ac System][model]" class="form-control form-control-lg" value="{{$acData['model'] ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="coldRoom">Cold Room</label>
                                        <input type="text" name="ozoneDepleting[Ac System][coldroom]" class="form-control form-control-lg" value="{{$acData['coldroom'] ?? ''}}">
                                    </div>
                                </div>
                                @can('projects.edit')
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button class="btn btn-primary float-right" type="submit">Save</button>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
  <form method="get" action="{{url('genratePdf/'.$project->id)}}">
    <input type="text" value="{{$project->id}}"/>
    <button type="submit">Submit</button>
  </form>
</div>

@push('js')
    <script>
        function handleFormSubmission(e, url) {
            e.preventDefault();

            let form = $(this); // Get the form element
            let submitButton = form.find(':submit');
            let originalText = submitButton.html();

            // Disable the submit button and change its text
            submitButton.text('Wait...');
            submitButton.prop('disabled', true);

            let formData = new FormData(form[0]); // Create FormData object from the form

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.isStatus) {
                        successMsg(`${response.message}`);
                    } else {
                        errorMsg("An unexpected error occurred. Please try again later.");
                    }
                },
                error: function(err) {
                    if (err.responseJSON && err.responseJSON.errors) {
                        let errors = err.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            $('#' + field + 'Error').text(messages[0]).show();
                            $('[name="' + field + '"]').addClass('is-invalid');
                        });
                    } else {
                        errorMsg("An unexpected error occurred. Please try again later.");
                    }
                },
                complete: function() {
                    // Re-enable the submit button and restore its original text
                    submitButton.text(originalText);
                    submitButton.prop('disabled', false);
                }
            });
        }

        $('#addReportMaterialForm').submit(function(e) {
            handleFormSubmission.call(this, e, "{{route('addReportMaterial')}}");
        });

        $('#PolychlorinatedForm').submit(function(e) {
            handleFormSubmission.call(this, e, "{{route('addReportMaterial')}}");
        });

        $('#ozoneDepletingForm').submit(function(e) {
            handleFormSubmission.call(this, e, "{{route('addReportMaterial')}}");
        });
    </script>
@endpush
