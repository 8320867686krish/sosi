@extends('layouts.app')

@section('css')
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap-select/css/bootstrap-select.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/select2/css/select2.css') }}"> --}}

    <style>
        #checkList {
            overflow: auto;
        }

        .zoom-tool-bar {
            bottom: 0px;
            width: 100%;
            height: 20px;
            right: 0;
            top: 0px;
            padding: 3px 0;
            font-size: 13px;
            /* color: #007cc0; */
            color: #008476 !important;
            accent-color: #008476 !important;
        }

        .zoom-tool-bar i {
            color: #008476 !important;
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
            width: 20px;
            height: 20px;
            background: #000;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 0 3px 0 rgba(0, 0, 0, .2);
            line-height: 20px;
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
                    <div class="col-12">
                        <a href="{{ route('projects.view', ['project_id' => $deck->project_id]) }}">
                            <i class="fas fa-arrow-left"></i> <b>Back</b>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-3">
                        <div class="card" id="checkList">
                            <h5 class="card-header">Checks List</h5>
                            <div class="card-body p-0">
                                <ul class="country-sales list-group list-group-flush" id="checkListUl">
                                    @include('check.checkList')
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-9">
                        <div class="zoom-tool-bar mb-5">
                            <div class="row">
                                <div class="col-sm-12 p-1 text-center zoominout">
                                    <span class="zoom-value">100%</span>
                                    <a href="javascript:;" title="Zoom Out" class="zoom-out" id="zoom-out"> <i
                                            class="fa fa-minus m-1"></i></a>
                                    <input class="mb-1 ranger" type="range" value="100" step="25" min="50"
                                        max="200">
                                    <a href="javascript:;" title="Zoom In" class="zoom-in" id="zoom-in"> <i
                                            class="fa fa-plus m-1"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="outfit">
                            <div class="target">
                                <img id="previewImg1" src="{{ $deck->image }}" alt="Upload Image">
                                <div id="showDeckCheck">
                                    @include('check.dot')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12" style="display: none;">
            <div class="col-12 col-md-12 col-lg-6 cloneTableTypeDiv" id="cloneTableTypeDiv">
                <label for="table_type" id="tableTypeLable"></label>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <select class="form-control" id="table_type" name="table_type">
                                <option value="Contained">Contained</option>
                                <option value="Not Contained">Not Contained</option>
                                <option value="PCHM">PCHM</option>
                                <option value="Unknown" selected>Unknown</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 imagehazmat">
                        <div class="form-group">
                            <input type="file" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('check.checkAddModal')
    </div>
@stop

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/vendor/bootstrap-select/js/bootstrap-select.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script> --}}

    <script>
        var isStopped = false;
        var initialLeft, initialTop;
        let widthPercent = 100;
        let previewImgInWidth = $("#previewImg1").width();
        let currectWithPercent = widthPercent;
        // let zoomValue = 100;

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
            let $dot = $(dot);

            // Remove the "selected" class from all dots
            $(".dot").removeClass("selected");

            // Add the "selected" class to the clicked dot
            $dot.addClass("selected");

            // Retrieve data attributes from the clicked dot
            let checkId = $dot.attr('data-checkId');
            let data = $dot.attr('data-check');

            // Populate form fields if data is available
            if (data) {
                var jsonObject = JSON.parse(data);
                for (var key in jsonObject) {
                    if (jsonObject.hasOwnProperty(key)) {
                        $("#" + key).val(jsonObject[key]);
                    }
                }
            }

            if (!checkId) {
                $("#chkName").hide();
            }
            // Show the modal box
            $("#checkDataAddModal").modal('show');
        }

        function dotsDrugUpdatePositionForDB(dot) {
            let $dots = $(dot);

            let check_id = $dots.attr('data-checkId');

            let position_left = parseFloat($dots.css('left')) * (100 / widthPercent);
            let position_top = parseFloat($dots.css('top')) * (100 / widthPercent);
            if (check_id && check_id != "0") {
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
                        $('#showSuccessMsg').fadeIn().delay(20000).fadeOut();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        }

        function updateZoomValue() {
            $('.zoom-value').text(widthPercent + '%');
        }

        function resetImagePx() {
            $(".target").css({
                left: "0px",
                top: "0px"
            });
        }

        function setDotPosition() {
            $(".dot").each(function(index) {
                let left = parseFloat($(this).css('left'));
                let top = parseFloat($(this).css('top'));

                left = left * (widthPercent / currectWithPercent);
                top = top * (widthPercent / currectWithPercent);

                $(this).css('left', left);
                $(this).css('top', top);
                $(this).width(20 * (widthPercent / 100));
                $(this).height(20 * (widthPercent / 100));
                $(this).css('line-height', 20 * (widthPercent / 100) + "px");
            });
        }

        function updateZoom(zoomValue) {
            let newWidth = previewImgInWidth * (zoomValue / 100);
            $("#previewImg1").width(newWidth);
            setDotPosition();
            updateZoomValue(zoomValue);
        }

        $(document).ready(function() {
            let checkId;
            $(".imagehazmat").hide();

            $("#checkList").css('height', $("#previewImg1").height());

            // $(".select2").select2({
            //     placeholder: "Select a hazmat",
            //     tags: true,
            //     tokenSeparators: [',', ' '],
            // });

            $('.target').draggable({
                stop: function(event, ui) {
                    isStopped = true;
                }
            });

            //Update zoom value when range input changes
            $('.ranger').on('input', function() {
                currectWithPercent = widthPercent;
                widthPercent = parseInt($(this).val());
                updateZoom(widthPercent);
            });

            $(document).on("click", "#zoom-in", function() {
                resetImagePx();
                currectWithPercent = widthPercent;
                if (widthPercent <= 175) {
                    widthPercent += 25;
                    let newWidth = previewImgInWidth * (widthPercent / 100);
                    $("#previewImg1").width(newWidth);
                    setDotPosition();
                    updateZoomValue();
                    $('.ranger').val(widthPercent);
                }
            });

            $(document).on("click", "#zoom-out", function() {
                resetImagePx();
                currectWithPercent = widthPercent;
                if (widthPercent >= 75) {
                    widthPercent -= 25;
                    let newWidth = previewImgInWidth * (widthPercent / 100);
                    $("#previewImg1").width(newWidth);
                    setDotPosition();
                    updateZoomValue();
                    $('.ranger').val(widthPercent);
                }
            });

            $(".outfit img").click(function(e) {
                e.preventDefault();
                if (!isStopped) {

                    var dot_count = $(".dot").length;

                    var top_offset = $(this).offset().top - $(window).scrollTop();
                    var left_offset = $(this).offset().left - $(window).scrollLeft();

                    var top_px = Math.round((e.clientY - top_offset - 10));
                    var left_px = Math.round((e.clientX - left_offset - 10));

                    var top_perc = top_px / $(this).height() * 100;
                    var left_perc = left_px / $(this).width() * 100;

                    var container_width = $(this).width();
                    var container_height = $(this).height();

                    var top_in_px = Math.round((top_perc / 100) * container_height);
                    var left_in_px = Math.round((left_perc / 100) * container_width);

                    var dot = '<div class="dot" style="top: ' + top_in_px + 'px; left: ' +
                        left_in_px +
                        'px;" id="dot_' + (dot_count + 1) + '">' + (dot_count + 1) + '</div>';

                    $(dot).hide().appendTo($(this).parent()).fadeIn(350, function() {
                        openAddModalBox(this); // Call the function with the newly created dot
                        makeDotsDraggable();
                    });

                    currectWithPercent = widthPercent;
                    setDotPosition();
                }

                if (isStopped) {
                    isStopped = false;
                }
            });

            makeDotsDraggable();

            $(document).on("click", ".dot", function() {
                openAddModalBox(this);
            });

            $(document).on("click", "#editCheckbtn", function(event) {
                event.stopPropagation(); // Prevents the click event from bubbling up to the parent .dot element
                let checkDataId = $(this).attr('data-dotId');
                let dotElement = $(`#${checkDataId}`)[0];
                openAddModalBox(dotElement);
            });

            // Add event listener for Save button click
            $(document).on("click", "#checkDataAddSubmitBtn", function() {
                var checkId = $(".dot.selected").attr('data-checkId');
                $("#id").val(checkId);

                // Get the position of the selected dot
                let position_left = parseFloat($(".dot.selected").css('left')) * (100 / widthPercent);
                let position_top = parseFloat($(".dot.selected").css('top')) * (100 / widthPercent);

                $("#position_left").val(position_left);
                $("#position_top").val(position_top);

                // If checkId is not available, create a new attribute "data-checkId" for the selected dot
                if (!checkId) {
                    checkId = 0; // Set a temporary value for the new checkId
                    $(".dot.selected").attr('data-checkId', checkId);
                }

                // Serialize form data
                let checkFormData = new FormData($("#checkDataAddForm")[0]);
                // console.log(checkformdata);
                // $("#checkdataaddform").serializearray();

                let $submitButton = $(this);
                let originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: $("#checkDataAddForm").attr('action'),
                    data: checkFormData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $(".dot.selected").attr('data-checkId', response.id);
                        var checkData = {
                            id: response.id,
                            name: response.name,
                            project_id: $("#project_id").val(),
                            deck_id: $("#deck_id").val(),
                            type: $("#type").val(),
                            suspected_hazmat: $("#suspected_hazmat").val(),
                            equipment: $("#equipment").val(),
                            component: $("#component").val(),
                            location: $("#location").val(),
                            sub_location: $("#sub_location").val(),
                            remarks: $("#remarks").val()
                        };

                        let checkDataJson = JSON.stringify(checkData);
                        $(".dot.selected").attr('data-check', checkDataJson);

                        $('#checkListUl').html();
                        $('#checkListUl').html(response.htmllist);

                        let messages = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                            ${response.message}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`;

                        $("#chkName").show();
                        $("#showSuccessMsg").html(messages);
                        $('#showSuccessMsg').fadeIn().delay(20000).fadeOut();
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                        $("#checkDataAddForm")[0].reset();
                        $("#id").val("");
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                        $("#checkDataAddForm")[0].reset();
                        $("#id").val("");
                    }
                });

                // Hide the modal box
                $("#checkDataAddModal").modal('hide');
            });

            $(document).on("click", "#checkDataAddCloseBtn", function() {
                $("#checkDataAddForm")[0].reset();
                $("#id").val("");
            });

            $('#suspected_hazmat').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                let selectedValue = $(this).find('option').eq(clickedIndex).val();

                if (!isSelected) {
                    $(`#cloneTableTypeDiv${selectedValue}`).remove();
                } else {
                    let clonedElement = $('#cloneTableTypeDiv').clone();
                    clonedElement.removeAttr("id");
                    clonedElement.attr("id", "cloneTableTypeDiv" + selectedValue);

                    clonedElement.find('label').text($(this).find('option').eq(clickedIndex).text());
                    clonedElement.find('select').attr('id', `table_type_${selectedValue}`).attr('name',`table_type[${selectedValue}]`);

                    clonedElement.find('input[type="file"]').prop({
                        id: `image_${selectedValue}`,
                        name: `image[${selectedValue}]`
                    });

                    // Append cloned element to showTableTypeDiv
                    $('#showTableTypeDiv').append(clonedElement);
                }
            });

            $("#showTableTypeDiv").on("change", ".cloneTableTypeDiv select", function() {
                let selectedValue = $(this).val();
                let cloneTableTypeDiv = $(this).closest(".cloneTableTypeDiv");

                // Find elements with either class col-12 or col-6 within cloneTableTypeDiv
                let targetElements = cloneTableTypeDiv.find(".col-12, .col-6");

                // Determine the class based on the selected value
                let newClass = selectedValue === "Unknown" ? "col-12" : "col-6";

                // Remove the existing class and add the new class for all targetElements
                targetElements.removeClass("col-12 col-6").addClass(newClass);

                // Toggle visibility of .imagehazmat based on the selected value
                cloneTableTypeDiv.find(".imagehazmat").toggle(selectedValue !== "Unknown");
            });


            $(document).on("click", ".deleteCheckbtn", function(e) {
                e.preventDefault();

                let href = $(this).attr("href");
                let checkId = $(this).data('id');
                let $liToDelete = $(this).closest('li'); // Get the closest <li> element
                // let $checkDiv = $(`div[data-checkid="${checkId}"]`);

                $.ajax({
                    type: 'GET',
                    url: href,
                    success: function(response) {
                        if (response.status) {
                            let messages = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                ${response.message}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>`;

                            $("#showSuccessMsg").html(messages).fadeIn().delay(20000)
                                .fadeOut();
                            $liToDelete.remove(); // Remove the <li> element
                            $(".dot").remove();
                            $('#showDeckCheck').html();
                            $('#checkListUl').html();
                            $('#showDeckCheck').html(response.htmldot);
                            $('#checkListUl').html(response.htmllist);
                            makeDotsDraggable();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting item:', error);
                    }
                });
            });
        });
    </script>
@endsection
