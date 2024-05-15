@if (!empty($hazmats))
    @foreach ($hazmats as $hazmat)
        @php
            $isEquipment = $hazmat->hazmat->equipment->count() > 0 ? true : false;
        @endphp

        <div class="col-12 col-md-12 col-lg-12 cloneTableTypeDiv" id="cloneTableTypeDiv{{ $hazmat->hazmat_id }}">
            <input type="hidden" id="hasid_{{ $hazmat->hazmat_id }}" name="hasid[{{ $hazmat->hazmat_id }}]"
                value="{{ $hazmat->id }}">
            <label for="table_type" id="tableTypeLable" class="mr-5 tableTypeLable">{{ $hazmat->hazmat->name }}</label>
            <div class="row">
                <div class="col-{{ $hazmat->type == 'Unknown' ? 12 : 4 }} table_typecol">
                    <div class="form-group">
                        <select class="form-control table_type tableType{{ $hazmat->hazmat_id }}"
                            id="table_type_{{ $hazmat->hazmat_id }}" name="table_type[{{ $hazmat->hazmat_id }}]">
                            <option value="Contained" {{ $hazmat->type == 'Contained' ? 'selected' : '' }}>Contained
                            </option>
                            <option value="Not Contained" {{ $hazmat->type == 'Not Contained' ? 'selected' : '' }}>
                                Not Contained</option>
                            <option value="PCHM" {{ $hazmat->type == 'PCHM' ? 'selected' : '' }}>
                                PCHM</option>
                            <option value="Unknown" {{ $hazmat->type == 'Unknown' ? 'selected' : '' }}>
                                Unknown</option>
                        </select>
                    </div>
                    <div style="{{ $hazmat->type == 'Unknown' ? 'display: none;' : '' }}" class="documentLoadCheckboxDiv" id="documentLoadCheckboxDiv_{{$hazmat->hazmat_id}}">
                        <input type="checkbox" id="equipmentcheckbox_{{$hazmat->hazmat_id}}" class="documentLoadCheckbox"
                            data-id="{{ $hazmat->hazmat_id }}">
                        <label for="myCheckbox">Load Document From Master Data</label>
                    </div>
                </div>

                <div class="col-4 imagehazmat" id="imagehazmat{{ $hazmat->hazmat_id }}">
                    <div class="form-group">
                        <input type="file" class="form-control" accept="*/*" id="image_{{ $hazmat->hazmat_id }}"
                            name="image[{{ $hazmat->hazmat_id }}]">
                    </div>
                    <div class="imageNameShow" style="font-size: 13px" id="imageNameShow_{{$hazmat->hazmat_id}}">
                        @if (basename($hazmat->getOriginal('image')) != $hazmat->project_id)
                            <a href="{{ $hazmat->image }}"
                                target="_black">{{ basename($hazmat->getOriginal('image')) }}</a> <a
                                href="{{ route('removeHazmatDocument', ['id' => $hazmat->id, 'type' => 'image']) }}"
                                class="text-danger font-size-20 float-right removeHazmatDocument">x</a>
                        @endif
                    </div>
                </div>

                <div class="col-4 dochazmat" id="dochazmat{{ $hazmat->hazmat_id }}">
                    <div class="form-group">
                        <input type="file" class="form-control" id="doc_{{ $hazmat->hazmat_id }}"
                            name="doc[{{ $hazmat->hazmat_id }}]">
                    </div>
                    <div style="font-size: 13px; margin-bottom: 10px;" id="docNameShow_{{$hazmat->hazmat_id}}">
                        @if (basename($hazmat->getOriginal('doc')) != $hazmat->project_id)
                            <a href="{{ $hazmat->doc }}"
                                target="_black">{{ basename($hazmat->getOriginal('doc')) }}</a> <a
                                href="{{ route('removeHazmatDocument', ['id' => $hazmat->id, 'type' => 'doc']) }}"
                                class="text-danger font-size-20 float-right removeHazmatDocument">x</a>
                        @endif
                    </div>
                </div>

                <div class="col-4 equipment">
                    <div class="form-group" id="equipmentDiv_{{$hazmat->hazmat_id}}">
                        <select class="form-control equipmentSelectTag" id="equipmentSelectTag_{{$hazmat->hazmat_id}}" name="equipmenttt[{{$hazmat->hazmat_id}}]" data-id="{{$hazmat->hazmat_id}}">
                            <option value="">Select Equipment</option>

                        </select>
                    </div>
                </div>
                <div class="col-4 manufacturer">
                    <div class="form-group" id="manufacturerDiv_{{$hazmat->hazmat_id}}">
                        <select class="form-control manufacturerSelectTag" data-id="{{$hazmat->hazmat_id}}" id="manufacturerSelectTag_{{$hazmat->hazmat_id}}" name="manufacturer[{{$hazmat->hazmat_id}}]">
                            <option value="">First Select Equipment</option>
                        </select>
                    </div>
                </div>
                <div class="col-4 modelMakePart">
                    <div class="form-group mb-3" id="modelMakePartDiv_{{$hazmat->hazmat_id}}">
                        <select class="form-control modelMakePartTag" data-id="{{$hazmat->hazmat_id}}" id="modelMakePartTag_{{$hazmat->hazmat_id}}" name="modelmakepart[{{$hazmat->hazmat_id}}]">
                            <option value="">First Select Manufacturer</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 remarks" style="{{ empty($hazmat->remarks) ? 'display: none;' : '' }}">
                    <div class="form-group">
                        <textarea class="form-control remarksTextarea" placeholder="Remark..." id="remarks_{{ $hazmat->hazmat_id }}"
                            name="remark[{{ $hazmat->hazmat_id }}]" rows="2">{{ $hazmat->remarks ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
