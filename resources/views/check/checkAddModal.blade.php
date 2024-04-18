<div class="modal fade" data-backdrop="static" id="checkDataAddModal" tabindex="-1" role="dialog"
    aria-labelledby="checkDataAddModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 50% !important; max-width: none !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Title</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close" id="checkDataAddCloseBtn">
                    <span aria-hidden="true">×</span>
                </a>
            </div>
            <form method="post" action="{{ route('addImageHotspots') }}" id="checkDataAddForm"
                enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="project_id" name="project_id" value="{{ $deck->project_id ?? '' }}">
                    <input type="hidden" id="deck_id" name="deck_id" value="{{ $deck->id ?? '' }}">
                    <input type="hidden" id="position_left" name="position_left">
                    <input type="hidden" id="position_top" name="position_top">
                    <div class="row">
                        <div class="col-12 col-md-6" id="chkName">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control @error('name') is-invalid @enderror">
                                    <option value>Select Type</option>
                                    <option value="sample">Sample</option>
                                    <option value="visual">Visual</option>
                                </select>
                                <div class="invalid-feedback error" id="nameError"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" id="location" name="location" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="sub_location">Sub Location</label>
                                <input type="text" id="sub_location" name="sub_location" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="equipment">Equipment</label>
                                <input type="text" id="equipment" name="equipment" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="component">Component</label>
                                <input type="text" id="component" name="component" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="suspected_hazmat">Suspected Hazmat</label>
                                <select class="form-control selectpicker" id="suspected_hazmat"
                                    name="suspected_hazmat[]" multiple>
                                    <option value="">Select Hazmat</option>
                                    @if (isset($hazmats) && $hazmats->count() > 0)
                                        @foreach ($hazmats as $hazmat)
                                            <option value="{{ $hazmat->id }}">{{ $hazmat->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 mb-3">
                            <div style="border: 2px solid black;" class="p-2">
                                <h5 class="text-center">Document Analysis Results</h5>
                                <div class="row" id="showTableTypeDiv">

                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="checkDataAddSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
