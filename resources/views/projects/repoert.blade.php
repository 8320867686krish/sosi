<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
    <div class="accrodion-regular">
        <div id="accordion4">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            <span class="fas fa-angle-down mr-3"></span>1. Asbestos
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion4">
                    <div class="card-body">
                        <form method="post" action="{{route('addReportMaterial')}}" novalidate id="addReportMaterialForm">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="project_id" value="{{ $project->id ?? '' }}" id="project_id">
                                <div class="col-12">
                                    <h4>Propeller shafting</h4>
                                </div>
                                <div class="form-group col-12 mb-3">
                                    <label for="component_shafting">Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="material['Propeller shafting']['component'][]" id="component_shafting" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="">Select Component</option>
                                        <option value="Packing with low pressure hydraulic piping flange">Packing with low pressure hydraulic piping flange</option>
                                        <option value="Packing with casing">Packing with casing</option>
                                        <option value="Clutch">Clutch</option>
                                        <option value="Brake lining">Brake lining</option>
                                        <option value="Synthetic stern tubes">Synthetic stern tubes</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="make">Make/Model</label>
                                    <input type="text" class="form-control form-control-lg" id="make_shafting" name="material['Propeller shafting']['model']" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label for="manufacturer_shafting">Manufacturer</label>
                                    <input type="text" class="form-control form-control-lg" id="manufacturer_shafting" name="material['Propeller shafting']['manufacturer']" autocomplete="off" {{ $readonly }}>
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Diesel engine</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label for="component_diesel">Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="material['Diesel engine']['component'][]" id="component_diesel" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing with piping flange">Packing with piping flange</option>
                                        <option value="Lagging material for fuel pipe">Lagging material for fuel pipe</option>
                                        <option value="Lagging material for exhaust pipe">Lagging material for exhaust pipe</option>
                                        <option value="Lagging material turbocharger">Lagging material turbocharger</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="make_diesel">Make/Model</label>
                                    <input type="text" class="form-control form-control-lg" id="make_diesel" name="material['Diesel engine']['model']" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label for="manufacturer_engine">Manufacturer</label>
                                    <input type="text" class="form-control form-control-lg" id="manufacturer_engine"
                                        name="material['Diesel engine']['manufacturer']" autocomplete="off" {{ $readonly }}>
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Boiler</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="material['Boiler']['component'][]" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="Insulation in combustion chamber">Insulation in combustion chamber</option>
                                        <option value="Packing for casing door">Packing for casing door</option>
                                        <option value="Lagging material for exhaust pipe">Lagging material for exhaust pipe</option>
                                        <option value="Gasket for manhole">Gasket for manhole</option>
                                        <option value="Gasket for hand hole">Gasket for hand hole</option>
                                        <option value="Gas shield packing for soot blower and other hole">Gas shield packing for soot blower and other hole</option>
                                        <option value="Packing with flange of piping and valve for steam line, exhaust line, fuel line and drain line">Packing with flange of piping and valve for steam line, exhaust line, fuel line and drain line</option>
                                        <option value="Lagging material for piping and valve of steam line, exhaust line, fuel line and drain line">Lagging material for piping and valve of steam line, exhaust line, fuel line and drain line</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label>Make/Model 1</label>
                                    <input type="text" class="form-control form-control-lg" name="material['boiler']['model'][]" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label>Manufacturer 1</label>
                                    <input type="text" class="form-control form-control-lg" name="material['Boiler']['manufacturer'][]" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6">
                                    <label for="make">Make/Model 2</label>
                                    <input type="text" class="form-control form-control-lg"
                                        name="material['Boiler']['model'][]" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label for="">Manufacturer 2</label>
                                    <input type="text" class="form-control form-control-lg" name="material['Boiler']['manufacturer'][]" autocomplete="off" {{ $readonly }}>
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Exhaust gas economizer</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="material['Exhaust gas economizer']['component'][]" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing for casing door">Packing for casing door</option>
                                        <option value="Packing with manhole">Packing with manhole</option>
                                        <option value="Packing with hand hole">Packing with hand hole</option>
                                        <option value="Gas shield packing for soot blower">Gas shield packing for soot blower</option>
                                        <option value="Packing with flange of piping and valve for steam line, exhaust line, fuel line and drain line">Packing with flange of piping and valve for steam line, exhaust line, fuel line and drain line</option>
                                        <option value="Lagging material for piping and valve of steam line, exhaust line, fuel line and drain line">Lagging material for piping and valve of steam line, exhaust line, fuel line and drain line</option>
                                    </select>
                                </div>
                                <div class="form-group col-12">
                                    <label for="make">Remark</label>
                                    <textarea name="material['Exhaust gas economizer']['remark']" id="" rows="3" class="form-control" {{ $readonly }}></textarea>
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Incinerator</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="material['Incinerator']['component'][]" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing for casing door">Packing for casing door</option>
                                        <option value="Packing with manhole">Packing with manhole</option>
                                        <option value="Packing with hand hole">Packing with hand hole</option>
                                        <option value="Lagging material for exhaust pipe">Lagging material for exhaust pipe</option>
                                        <option value="Lagging material for exhaust pipe">Lagging material for exhaust pipe</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label>Make/Model</label>
                                    <input type="text" class="form-control form-control-lg"
                                        name="material['Incinerator']['model']" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label>Manufacturer</label>
                                    <input type="text" class="form-control form-control-lg" name="material['Incinerator']['manufacturer']" autocomplete="off" {{ $readonly }}>
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Auxiliary machinery (pump, compressor, oil purifier, crane)</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="material['Auxiliary machinery']['component'][]" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing for casing door and valve">Packing for casing door and valve</option>
                                        <option value="Gland packing">Gland packing</option>
                                        <option value="Brake lining">Brake lining</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="make">Make/Model 1</label>
                                    <input type="text" class="form-control form-control-lg" name="material['Auxiliary machinery']['model'][]" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label>Manufacturer 1</label>
                                    <input type="text" class="form-control form-control-lg" name="material['Auxiliary machinery']['manufacturer'][]" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6">
                                    <label for="make">Make/Model 2</label>
                                    <input type="text" class="form-control form-control-lg" name="material['Auxiliary machinery']['model'][]" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label>Manufacturer 2</label>
                                    <input type="text" class="form-control form-control-lg" name="material['Auxiliary machinery']['manufacturer'][]" autocomplete="off" {{ $readonly }}>
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Heat exchanger</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-6 mb-3">
                                    <label>Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="material['Heat exchanger']['component'][]" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="Packing with casing">Packing with casing</option>
                                        <option value="Gland packing for valve">Gland packing for valve</option>
                                        <option value="Lagging material and insulation">Lagging material and insulation</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label>Type</label>
                                    <input type="text" class="form-control form-control-lg" name="material['Heat exchanger']['type']" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6">
                                    <label>Make/Model</label>
                                    <input type="text" class="form-control form-control-lg" name="material['Heat exchanger']['model']" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label>Manufacturer</label>
                                    <input type="text" class="form-control form-control-lg"
                                        name="material['Heat exchanger']['manufacturer']" autocomplete="off" {{ $readonly }}>
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Valve Type</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-6 mb-3">
                                    <label for="component">Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="component[]" id="component" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="">Select Component</option>
                                        <option value="">Gland packing with valve, sheet packing with piping
                                            flange</option>
                                        <option value="">Gasket with flange of high pressure and/or high
                                            temperature</option>
                                        <option value="">Lagging material and insulation</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="make">Make/Model</label>
                                    <input type="text" class="form-control form-control-lg" id="make"
                                        name="make" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label for="assign_date">Manufacturer</label>
                                    <input type="text" class="form-control form-control-lg" id="end_date"
                                        name="end_date" autocomplete="off" {{ $readonly }}>
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Inert gas system</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-6 mb-3">
                                    <label for="component">Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="component[]" id="component" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="">Select Component</option>
                                        <option value="">Packing for casing, etc.</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="make">Make/Model</label>
                                    <input type="text" class="form-control form-control-lg" id="make"
                                        name="make" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label for="assign_date">Manufacturer</label>
                                    <input type="text" class="form-control form-control-lg" id="end_date"
                                        name="end_date" autocomplete="off" {{ $readonly }}>
                                </div>
                            </div>
                            <div class="border-top">
                                <h4 class="mt-3">Air conditioning system</h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-6 mb-3">
                                    <label for="component">Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="component[]" id="component" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="">Select Component</option>
                                        <option value="">Sheet packing, lagging material for piping and flexible
                                            joint.</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="make">Make/Model</label>
                                    <input type="text" class="form-control form-control-lg" id="make"
                                        name="make" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label for="assign_date">Manufacturer</label>
                                    <input type="text" class="form-control form-control-lg" id="end_date"
                                        name="end_date" autocomplete="off" {{ $readonly }}>
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
                        <form method="post" id="laboratoryAssignForm">
                            @csrf
                            <h4>Transformer</h4>
                            <div class="row">
                                <div class="form-group col-6 mb-3">
                                    <label for="component">Component</label>
                                    <select class="selectpicker show-tick form-control form-control-lg"
                                        name="component[]" id="component" multiple data-live-search="true"
                                        data-actions-box="true" {{ $readonly }}>
                                        <option value="">Select Component</option>
                                        <option value="">Insulating oil</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="make">Make/Model</label>
                                    <input type="text" class="form-control form-control-lg" id="make"
                                        name="make" autocomplete="off" {{ $readonly }}>
                                </div>
                                <div class="form-group col-6 mb-4">
                                    <label for="assign_date">Manufacturer</label>
                                    <input type="text" class="form-control form-control-lg" id="end_date"
                                        name="end_date" autocomplete="off" {{ $readonly }}>
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
