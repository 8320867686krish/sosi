@if (!$labs->isEmpty())
    @foreach ($labs as $lab)
        <input type="hidden" id="labid_{{ $lab->hazmat_id }}" name="labid[{{ $lab->hazmat_id }}]"
               value="{{ $lab->id ?? '' }}">
    @endforeach
@endif

@foreach ($labs->isEmpty() ? $hazmats : $labs as $lab)
    <div class="col-12 col-md-12 col-lg-12 cloneIHMTableDiv card" id="cloneIHMTableDiv{{ $lab->hazmat_id }}">
        <label for="ihm_table" id="ihmTableLable" class="mr-5 mt-3 card-header ihmTableLable">
            {{ $lab->hazmat->name }}
        </label>
        <div class="row card-body">
            <div class="col-{{ $lab->type == 'Contained' || $lab->type == 'PCHM' ? 6 : 12 }}  IHMTypeDiv">
                <div class="form-group mb-3">
                    <select class="form-control IHM_type" id="IHM_type_{{ $lab->hazmat_id }}" name="IHM_type[{{ $lab->hazmat_id }}]">
                        <option value="">Select Type</option>
                        <option value="Contained" {{ $lab->type == 'Contained' ? 'selected' : '' }}>Contained</option>
                        <option value="PCHM" {{ $lab->type == 'PCHM' ? 'selected' : '' }}>PCHM</option>
                        <option value="Not Contained" {{ $lab->type == 'Not Contained' ? 'selected' : '' }}>Not Contained</option>
                        <option value="Below Threshold" {{ $lab->type == 'Below Threshold' ? 'selected' : '' }}>Below Threshold</option>
                    </select>
                </div>
            </div>
            <div class="col-6 IHMPartDiv" style="{{ $lab->type == 'Contained' || $lab->type == 'PCHM' ? '' : 'display: none;' }}">
                <div class="form-group">
                    <select class="form-control IHM_part" id="IHM_part_{{ $lab->hazmat_id }}" name="IHM_part[{{ $lab->hazmat_id }}]">
                        <option value="">Select IHM Part</option>
                        <option value="IHMPart1" {{ $lab->IHM_part == 'IHMPart1' ? 'selected' : '' }}>IHM Part 1</option>
                        <option value="IHMPart2" {{ $lab->IHM_part == 'IHMPart2' ? 'selected' : '' }}>IHM Part 2</option>
                        <option value="IHMPart3" {{ $lab->IHM_part == 'IHMPart3' ? 'selected' : '' }}>IHM Part 3</option>
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <input type="text" class="form-control unit" placeholder="Unit..."
                           id="unit_{{ $lab->hazmat_id }}" name="unit[{{ $lab->hazmat_id }}]"
                           value="{{ $lab->unit ?? '' }}">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <input type="text" class="form-control number" placeholder="Number..."
                           id="number_{{ $lab->hazmat_id }}" name="number[{{ $lab->hazmat_id }}]"
                           value="{{ $lab->number ?? '' }}">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group mb-3">
                    <input type="text" class="form-control total" placeholder="Total (KG.)"
                           id="total_{{ $lab->hazmat_id }}" name="total[{{ $lab->hazmat_id }}]"
                           value="{{ $lab->total ?? '' }}">
                </div>
            </div>
            <div class="col-12 lab_remarks">
                <div class="form-group mb-3">
                    <textarea class="form-control labRemarksTextarea" rows="2" placeholder="Remark..."
                              id="lab_remarks_{{ $lab->hazmat_id }}" name="lab_remarks[{{ $lab->hazmat_id }}]">{{ $lab->lab_remarks ?? '' }}</textarea>
                </div>
            </div>

        </div>
    </div>
@endforeach
