@extends('layouts.app')

@section('css')
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">


    <style>
        #box {
            width: 400px;
            height: 300px;
            margin: 50px auto;
            border: 3px solid #222;
            background: #fafafa;
            position: relative;
            overflow: hidden;
            border-radius: 5px;
        }

        #box>img {
            width: 300px;
            height: 225px;
        }

        .output {
            padding: 10px 0;
            color: #fff;
            background: #525252;
            width: 100%;
            padding-left: 5px;
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
            /* background: rgba(white, 1); */
            background: #000000;
            /* background: #fff; */
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 0 3px 0 rgba(0, 0, 0, .2);
            line-height: 24px;
            font-size: 12px;
            font-weight: bold;
            transition: box-shadow .214s ease-in-out, transform .214s ease-in-out, background .214s ease-in-out;
            text-align: center;

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
                    {{-- <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Role</a></li>
                                <!-- <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Pages</a></li> -->
                                <!-- <li class="breadcrumb-item active" aria-current="page">Blank Pageheader</li> -->
                            </ol>
                        </nav>
                    </div> --}}
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        {{-- <div class="row"> --}}
        {{-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> --}}
        @include('layouts.message')
        <div class="card">
            <div class="card-body">
                <form id="imageForm" action="{{ route('addImageHotspots') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        {{-- <input type="hidden" name="id" id="imageId"> --}}
                        <input type="hidden" name="project_id" value="{{ $deck->project_id ?? '' }}">
                        <input type="hidden" name="deck_id" value="{{ $deck->id ?? '' }}">
                    </div>

                    <div class="outfit">
                        <div class="target">
                            <img id="previewImg1" src="{{ $deck->image }}" alt="Upload Image">
                            @foreach ($deck->checks as $dot)
                                <div class="dot ui-draggable ui-draggable-handle" data-checkId="{{ $dot->id }}"
                                    data-check="{{ $dot }}"
                                    style="top: {{ $dot->position_top }}px; left: {{ $dot->position_left }}px;"
                                    id="dot_{{ $loop->iteration }}">{{ $loop->iteration }}</div>
                            @endforeach
                        </div>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-primary float-right formSubmitBtn">Save</button>
                    </div>
                </form>
                <div class="output">Dot Positions goes here.</div>
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
                    <form method="post" id="checkDataAddForm">
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
                                        <input type="text" id="suspected_hazmat" name="suspected_hazmat"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="equipment">Equipment</label>
                                        <input type="text" id="equipment" name="equipment" class="form-control">
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




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
    <script src="{{ asset('assets/vendor/dragZoom.js') }}"></script>
    <script>
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

                    var output =
                        `Left: ${new_left_in_px}px; Top: ${new_top_in_px} px;`;

                    $(this).css("left", new_left_perc);
                    $(this).css("top", new_top_perc);

                    $('.output').html('Position in Pixels: ' + output);
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

        let checkId;

        $(document).ready(function() {

            $('.target').dragZoom({
                scope: $("body"),
                zoom: 1,
            });

            let imageWidth = $('#previewImg1').width();
            $('.output').css('max-width', imageWidth);

            $(".outfit img").click(function(e) {
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

                var dot = '<div class="dot" style="top: ' + top_in_px + 'px; left: ' + left_in_px +
                    'px;" id="dot_' + (dot_count + 1) + '">' + (dot_count + 1) + '</div>';

                $(dot).hide().appendTo($(this).parent()).fadeIn(350, function() {
                    openAddModalBox(this); // Call the function with the newly created dot
                    makeDotsDraggable();
                });

                $('.output').html("Position in Pixels: Left: " + left_in_px + "px; Top: " + top_in_px +
                    "px;");
            });

            makeDotsDraggable();

            $("#imageForm").submit(function(e) {
                e.preventDefault();

                let $submitButton = $(this).find('button[type="submit"]');
                let originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);

                let formData = new FormData(this);

                let dots = [];
                $(".dot").each(function(index) {
                    let containerWidth = $(this).parent().width();
                    let containerHeight = $(this).parent().height();

                    let left = parseFloat($(this).css('left'));
                    let top = parseFloat($(this).css('top'));

                    dots.push({
                        position_left: left,
                        position_top: top
                    });
                });

                formData.append('dots', JSON.stringify(dots));

                // $.ajax({
                //     url: $(this).attr('action'),
                //     method: 'POST',
                //     data: formData,
                //     contentType: false,
                //     processData: false,
                //     success: function(response) {
                //         if (response.isStatus) {
                //             setTimeout(function() {
                //                 window.location.href =
                //                     "{{ route('projects.view', ['project_id']) }}/" +
                //                     response.project_id;
                //             }, 3000);
                //         }
                //     },
                //     error: function(xhr, status, error) {
                //         console.error(xhr.responseText);
                //         // Handle error response
                //         $submitButton.html(originalText);
                //         $submitButton.prop('disabled', false);
                //     }
                // });
            });

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
                    equipment: $("#equipment").val()
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
                let originalText = $(this).html();
                console.log(queryString);
                // $submitButton.text('Wait...');
                // $submitButton.prop('disabled', true);

                // $.ajax({
                //     type: 'POST',
                //     url: '',
                //     data: formData,
                //     success: function(response) {
                //         alert(response.message); // Show success message
                //     },
                //     error: function(xhr, status, error) {
                //         console.error(xhr.responseText);
                //     }
                // });

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
