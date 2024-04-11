@extends('layouts.app')

@section('css')
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap-select/css/bootstrap-select.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/css/select2.css') }}">

    <style>
        .zoom-tool-bar {
            bottom: 0px;
            width: 100%;
            height: 20px;
            right: 0;
            top: 0px;
            padding: 3px 0;
            font-size: 13px;
            color: #007cc0;
        }

        .zoom-tool-bar i {
            color: #007cc0;
            font-size: 16px;
        }

        .zoom-tool-bar input {
            width: 20%;
        }

        .outfit {
            line-height: 0;
            position: relative;
            width: 100%;
            height: auto;
            display: inline-block;
            overflow: hidden;

            img {
                height: auto;
                cursor: pointer;
            }
        }

        .dot {
            position: absolute;
            width: 24px;
            height: 24px;
            background: #000;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 0 3px 0 rgba(0, 0, 0, .2);
            line-height: 24px;
            font-size: 12px;
            font-weight: bold;
            transition: box-shadow .214s ease-in-out, transform .214s ease-in-out, background .214s ease-in-out;
            text-align: center;
            opacity: 0.7;

            &.ui-draggable-dragging {
                box-shadow: 0 0 25px 0 rgba(0, 0, 0, .5);
                transform: scale3d(1.2, 1.2, 1.2);
                background: rgba(white, .7);
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Check Management</h2>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        {{-- <div class="row"> --}}
        {{-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> --}}
        @include('layouts.message')
        <div id="showSuccessMsg"></div>
        <div class="card">
            {{-- <div class="zoom-tool-bar"></div> --}}
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="card" id="checkList">
                            <h5 class="card-header">Checks list</h5>
                            <div class="card-body p-0">
                                <ul class="country-sales list-group list-group-flush" id="checkListUl">
                                    @foreach ($deck->checks as $dot)
                                        <li class="country-sales-content list-group-item">
                                            <span class="mr-2">
                                                <i class="flag-icon flag-icon-us" title="us" id="us"></i>
                                            </span>
                                            <span class="">{{ $loop->iteration }}.{{ $dot->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <button class="btn btn-primary" id="zoom-out">Out</button>
                        <button class="btn btn-primary" id="zoom-in">In</button>
                        <div class="outfit">
                            <div class="target">
                                <img id="previewImg1" src="{{ $deck->image }}" alt="Upload Image">
                                @foreach ($deck->checks as $dot)
                                    <div class="dot ui-draggable ui-draggable-handle" data-checkId="{{ $dot->id }}"
                                        data-check="{{ $dot }}"
                                        style="top: {{ $dot->position_top - ($deck->isApp == 1 ? 24 : 0) }}px; left: {{ $dot->position_left - ($deck->isApp == 1 ? 24 : 0) }}px;"
                                        id="dot_{{ $loop->iteration }}">
                                        {{ $loop->iteration }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <form method="post" action="{{ route('addImageHotspots') }}" id="checkDataAddForm">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" id="id" name="id">
                            <input type="hidden" name="project_id" value="{{ $deck->project_id ?? '' }}">
                            <input type="hidden" name="deck_id" value="{{ $deck->id ?? '' }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="sample">Sample</option>
                                            <option value="visual">Visual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <input type="text" id="description" name="description" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="compartment">Compartment</label>
                                        <input type="text" id="compartment" name="compartment" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="material">Material</label>
                                        <input type="text" id="material" name="material" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="color">Color</label>
                                        <input type="text" id="color" name="color" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="suspected_hazmat">Suspected Hazmat</label>
                                        {{-- <input type="text" class="form-control" id="suspected_hazmat" name="suspected_hazmat"> --}}
                                        <select class="form-control select2" id="suspected_hazmat"
                                            name="suspected_hazmat" multiple="multiple">
                                            <option value="">Select Hazmat</option>
                                            @if (isset($hazmats) && $hazmats->count() > 0)
                                                @foreach ($hazmats as $hazmat)
                                                    <option value="{{ $hazmat->id }}">{{ $hazmat->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
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
                                        <label for="position">Position</label>
                                        <input type="text" id="position" name="position" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="sub_position">Sub Position</label>
                                        <input type="text" id="sub_position" name="sub_position"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
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
    </div>
@stop

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/vendor/bootstrap-select/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/content-zoom-slider.min.js') }}"></script> --}}

    <script>
        var isStopped = false;
        var initialLeft, initialTop;

        function makeDotsDraggable() {
            $(".dot").draggable({
                containment: "#previewImg1",
                stop: function(event, ui) {
                    var new_left_perc = parseInt($(this).css("left")) + "px";
                    var new_top_perc = parseInt($(this).css("top")) + "px";

                    var new_left_in_px = Math.round((parseInt($(this).css(
                        "left"))));
                    var new_top_in_px = Math.round((parseInt($(this).css(
                        "top"))));

                    $(this).css("left", new_left_perc);
                    $(this).css("top", new_top_perc);

                    dotsDrugUpdatePositionForDB(this);
                }
            });
        }

        function openAddModalBox(dot) {
            var $dot = $(dot);

            // Remove the "selected" class from all dots
            $(".dot").removeClass("selected");

            // Add the "selected" class to the clicked dot
            $dot.addClass("selected");

            // Retrieve data attributes from the clicked dot
            var checkId = $dot.attr('data-checkId');
            var data = $dot.attr('data-check');

            // Populate form fields if data is available
            if (data) {
                var jsonObject = JSON.parse(data);
                for (var key in jsonObject) {
                    if (jsonObject.hasOwnProperty(key)) {
                        $("#" + key).val(jsonObject[key]);
                    }
                }
            }

            // Show the modal box
            $("#checkDataAddModal").modal('show');
        }

        function dotsDrugUpdatePositionForDB(dot) {
            let $dots = $(dot);

            let check_id = $dots.attr('data-checkId');

            let position_left = parseFloat($dots.css('left'));
            let position_top = parseFloat($dots.css('top'));
            if (check_id && check_id != "new") {
                $.ajax({
                    url: "{{ route('addImageHotspots') }}",
                    method: 'POST',
                    data: {
                        id: check_id,
                        position_left: position_left,
                        position_top: position_top,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        let messages = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>`;

                        $("#showSuccessMsg").html(messages);
                        $('.showSuccessMsg').fadeIn().delay(20000).fadeOut();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        }

        $(document).ready(function() {
            let checkId;

            $(".select2").select2({
                placeholder: "Select a hazmat",
                tags: true,
                tokenSeparators: [',', ' '],
            });

            // Store initial position of the image
            // $("#previewImg1").contentZoomSlider({
            //     toolContainer: ".zoom-tool-bar",
            // });

            function reset() {
                $(".target").css({
                    left: "0px",
                    top: "0px"
                });
            }

            $(".zoom-in").click(function() {
                reset();
            });

            $(".zoom-out").click(function() {
                reset();
            });

            $('.target').draggable({
                stop: function(event, ui) {
                    // Add your code here to handle the drag stop event
                    isStopped = true;
                }
            });

            let imageWidth = $('#previewImg1').width();
            /// $('.output').css('max-width', imageWidth);

            let widthPercent = 100;
            let previewImgInWidth = $("#previewImg1").width();
            let previewImgInHeight = $("#previewImg1").height();
            let dotPositions = [];

            $(".dot").each(function() {
                let left = parseFloat($(this).css('left'));
                let top = parseFloat($(this).css('top'));
                dotPositions.push({
                    left: left,
                    top: top
                });
            });

            $(document).on("click", "#zoom-in", function() {
                reset();
                if (widthPercent <= 175) {
                    widthPercent += 25;
                    // Update image size
                    let newWidth = previewImgInWidth * (widthPercent / 100);
                    let newHeight = previewImgInHeight * (widthPercent / 100);

                    $("#previewImg1").width(newWidth);
                    $("#previewImg1").height(newHeight);

                    // Update dot positions
                    $(".dot").each(function(index) {
                        let left = dotPositions[index].left * (widthPercent / 100);
                        let top = dotPositions[index].top * (widthPercent / 100);
                        $(this).css('left', left);
                        $(this).css('top', top);
                        $(this).width(24 * (widthPercent / 100));
                        $(this).height(24 * (widthPercent / 100));
                        $(this).css('line-height', 24 * (widthPercent / 100) + "px");
                    });
                }
            });

            $(document).on("click", "#zoom-out", function() {
                reset();
                if (widthPercent >= 75) {
                    widthPercent -= 25;

                    // Update image size
                    let newWidth = previewImgInWidth * (widthPercent / 100);
                    let newHeight = previewImgInHeight * (widthPercent / 100);

                    $("#previewImg1").width(newWidth);
                    $("#previewImg1").height(newHeight);

                    // Update dot positions
                    $(".dot").each(function(index) {
                        let left = dotPositions[index].left * (widthPercent / 100);
                        let top = dotPositions[index].top * (widthPercent / 100);
                        $(this).css('left', left);
                        $(this).css('top', top);
                        $(this).width(24 * (widthPercent / 100));
                        $(this).height(24 * (widthPercent / 100));
                        $(this).css('line-height', 24 * (widthPercent / 100) + "px");
                    });
                }
            });

            $(".outfit img").click(function(e) {

                if (!isStopped) {

                    var dot_count = $(".dot").length;

                    var top_offset = $(this).offset().top - $(window).scrollTop();
                    var left_offset = $(this).offset().left - $(window).scrollLeft();

                    var top_px = Math.round((e.clientY - top_offset - 12));
                    var left_px = Math.round((e.clientX - left_offset - 12));

                    var top_perc = top_px / $(this).height() * 100;
                    var left_perc = left_px / $(this).width() * 100;

                    var container_width = $(this).width();
                    var container_height = $(this).height();

                    var top_in_px = Math.round((top_perc / 100) * container_height);
                    var left_in_px = Math.round((left_perc / 100) * container_width);

                    var dot = '<div class="dot" style="top: ' + top_in_px + 'px; left: ' +
                        left_in_px +
                        'px;" id="dot_' + (dot_count + 1) + '">' + (dot_count + 1) + '</div>';

                        dotPositions.push({
                            left: left_in_px,
                            top: top_in_px
                        });

                    $(dot).hide().appendTo($(this).parent()).fadeIn(350, function() {
                        openAddModalBox(this); // Call the function with the newly created dot
                        makeDotsDraggable();
                    });
                }
                if (isStopped) {
                    isStopped = false;
                }

            });

            makeDotsDraggable();

            $(document).on("click", ".dot", function() {
                openAddModalBox(this);
            });

            // Add event listener for Save button click
            $(document).on("click", "#checkDataAddSubmitBtn", function() {

                var checkData = {
                    id: $("#id").val(),
                    name: $("#name").val(),
                    type: $("#type").val(),
                    description: $("#description").val(),
                    compartment: $("#compartment").val(),
                    material: $("#material").val(),
                    color: $("#color").val(),
                    suspected_hazmat: $("#suspected_hazmat").val(),
                    equipment: $("#equipment").val(),
                    equipment: $("#component").val(),
                    equipment: $("#position").val(),
                    equipment: $("#sub_position").val(),
                    equipment: $("#remarks").val()
                };

                var checkDataJson = JSON.stringify(checkData);

                // If checkId is not available, create a new attribute "data-checkId" for the selected dot
                if (!checkId) {
                    checkId = "new"; // Set a temporary value for the new checkId
                    $(".dot.selected").attr('data-checkId', checkId);
                }

                // Update the "data-check" attribute of the selected dot
                $(".dot.selected").attr('data-check', checkDataJson);

                // Serialize form data
                let checkFormData = $("#checkDataAddForm").serializeArray();

                // Get the position of the selected dot
                let position_left = parseFloat($(".dot.selected").css('left'));
                let position_top = parseFloat($(".dot.selected").css('top'));

                // Append position data to formData
                checkFormData.push({
                    name: 'position_left',
                    value: position_left
                });

                checkFormData.push({
                    name: 'position_top',
                    value: position_top
                });

                // Convert formData to query string
                let queryString = $.param(checkFormData);

                let $submitButton = $(this);
                let originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: $("#checkDataAddForm").attr('action'),
                    data: checkFormData,
                    success: function(response) {
                        // alert(response.message); // Show success message
                        $(".dot.selected").attr('data-checkId', response.id);
                        // $("#id").val(response.id);
                        let messages = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                            ${response.message}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`;

                        $("#showSuccessMsg").html(messages);
                        $('.showSuccessMsg').fadeIn().delay(20000).fadeOut();
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                    }
                });

                // Reset form fields, including hidden inputs, to their default values
                $("#checkDataAddForm")[0].reset();
                $("#id").val("");

                // Hide the modal box
                $("#checkDataAddModal").modal('hide');
            });


            $(document).on("click", "#checkDataAddCloseBtn", function() {
                // Reset form fields to their default values
                $("#checkDataAddForm")[0].reset();
                $("#id").val("");
            });
        });
    </script>
@endsection
