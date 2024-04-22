@if (!empty($hazmats))
    @foreach ($hazmats as $hazmat)
        <div class="col-12 col-md-12 col-lg-6 cloneTableTypeDiv" id="cloneTableTypeDiv{{ $hazmat->hazmat_id }}">
            <input type="hidden" id="hasid_{{$hazmat->hazmat_id}}" name="hasid[{{$hazmat->hazmat_id}}]" value="{{$hazmat->id}}">
            <label for="table_type" id="tableTypeLable">{{$hazmat->hazmat->name}}</label>
            <div class="row">
                <div class="col-{{ $hazmat->type == 'Unknown' ? 12 : 6 }}">
                    <div class="form-group">
                        <select class="form-control tableType{{$hazmat->hazmat_id}}" id="table_type_{{$hazmat->hazmat_id}}" name="table_type[{{$hazmat->hazmat_id}}]">
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
                </div>

                <div class="col-6 imagehazmat" id="imagehazmat{{$hazmat->hazmat_id}}">
                    <div class="form-group">
                        <input type="file" class="form-control" accept="image/*" id="image_{{$hazmat->hazmat_id}}" name="image[{{$hazmat->hazmat_id}}]">
                    </div>
                    <img src="{{ $hazmat->image }}" alt="" width="150px">
                </div>
            </div>
        </div>
    @endforeach
@endif

