@extends('layouts.app')

@section('css')
    <style>
        .output {
            padding: 10px 0;
            color: #fff;
            background: #525252;
            width: 100%;
            /* max-width: 420px; */
            padding-left: 5px;
        }

        .outfit {
            line-height: 0;
            position: relative;
            width: 100%;
            height: auto;
            background: gray;
            display: inline-block;
            overflow-x: auto;

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
                        <img id="previewImg1" src="{{ $deck->imagePath }}" alt="Upload Image">
                        @foreach ($deck->checks as $dot)
                            <div class="dot ui-draggable ui-draggable-handle" style="top: {{ $dot->position_top }}px; left: {{ $dot->position_left }}px;">{{ $loop->iteration }}</div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary float-right formSubmitBtn">Save</button>
                    </div>
                </form>
                <div class="output">Dot Positions goes here.</div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function() {
            let imageWidth = $('#previewImg1').width();
            $('.output').css('max-width', imageWidth);

            // $(".outfit img").click(function(e) {
            //     var dot_count = $(".dot").length;

            //     var top_offset = $(this).offset().top - $(window).scrollTop();
            //     var left_offset = $(this).offset().left - $(window).scrollLeft();

            //     var top_px = Math.round((e.clientY - top_offset - 12));
            //     var left_px = Math.round((e.clientX - left_offset - 12));

            //     var top_perc = top_px / $(this).height() * 100;
            //     var left_perc = left_px / $(this).width() * 100;

            //     // alert('Top: ' + top_px + 'px = ' + top_perc + '%');
            //     // alert('Left: ' + left_px + 'px = ' + left_perc + '%');

            //     var dot = '<div class="dot" style="top: ' + top_perc + '%; left: ' + left_perc + '%;">' + (dot_count + 1) + '</div>';

            //     $(dot).hide().appendTo($(this).parent()).fadeIn(350);

            //     $(".dot").draggable({
            //         containment: ".outfit",
            //         stop: function(event, ui) {
            //             var new_left_perc = parseInt($(this).css("left")) / ($(".outfit")
            //                     .width() / 100) +
            //                 "%";
            //             var new_top_perc = parseInt($(this).css("top")) / ($(".outfit")
            //                     .height() / 100) +
            //                 "%";
            //             var output = 'Top: ' + parseInt(new_top_perc) + '%, Left: ' + parseInt(
            //                 new_left_perc) + '%';

            //             $(this).css("left", parseInt($(this).css("left")) / ($(".outfit")
            //                     .width() / 100) +
            //                 "%");
            //             $(this).css("top", parseInt($(this).css("top")) / ($(".outfit")
            //                     .height() / 100) +
            //                 "%");

            //             $('.output').html('CSS Position: ' + output);
            //         }
            //     });

            //     // console.log("Left: " + left_perc + "%; Top: " + top_perc + '%;');
            //     $('.output').html("CSS Position: Left: " + parseInt(left_perc) + "%; Top: " + parseInt(
            //             top_perc) +
            //         '%;');
            // });

            // $(".outfit img").click(function(e) {
            //     var dot_count = $(".dot").length;

            //     var top_offset = $(this).offset().top - $(window).scrollTop();
            //     var left_offset = $(this).offset().left - $(window).scrollLeft();

            //     var top_px = Math.round((e.clientY - top_offset - 12));
            //     var left_px = Math.round((e.clientX - left_offset - 12));

            //     var top_perc = top_px / $(this).height() * 100;
            //     var left_perc = left_px / $(this).width() * 100;

            //     var container_width = $(this).width();
            //     var container_height = $(this).height();

            //     var top_in_px = Math.round((top_perc / 100) * container_height);
            //     var left_in_px = Math.round((left_perc / 100) * container_width);

            //     var dot = '<div class="dot" style="top: ' + top_in_px + 'px; left: ' + left_in_px +
            //         'px;">' + (dot_count + 1) + '</div>';

            //     $(dot).hide().appendTo($(this).parent()).fadeIn(350, function() {
            //         $(".dot").draggable({
            //             containment: ".outfit",
            //             stop: function(event, ui) {
            //                 var new_left_perc = parseInt($(this).css("left")) + "px";
            //                 var new_top_perc = parseInt($(this).css("top")) + "px";

            //                 var new_left_in_px = Math.round((parseInt($(this).css(
            //                     "left"))));
            //                 var new_top_in_px = Math.round((parseInt($(this).css(
            //                     "top"))));

            //                 var output =
            //                     `Left: ${new_left_in_px}px; Top: ${new_top_in_px} px;`;

            //                 $(this).css("left", new_left_perc);
            //                 $(this).css("top", new_top_perc);

            //                 $('.output').html('Position in Pixels: ' + output);
            //             }
            //         });
            //     });

            //     $('.output').html("Position in Pixels: Left: " + left_in_px + "px; Top: " + top_in_px +
            //         "px;");
            // });

            // $("#imageForm").submit(function(e) {
            //     e.preventDefault();

            //     let $submitButton = $(this).find('button[type="submit"]');
            //     let originalText = $submitButton.html();
            //     $submitButton.text('Wait...');
            //     $submitButton.prop('disabled', true);

            //     let formData = new FormData(this);

            //     let dots = [];
            //     $(".dot").each(function(index) {
            //         let containerWidth = $(this).parent().width();
            //         let containerHeight = $(this).parent().height();

            //         let left = parseFloat($(this).css('left'));
            //         let top = parseFloat($(this).css('top'));

            //         dots.push({
            //             position_left: left,
            //             position_top: top
            //         });
            //     });

            //     formData.append('dots', JSON.stringify(dots));

            //     $.ajax({
            //         url: $(this).attr('action'),
            //         method: 'POST',
            //         data: formData,
            //         contentType: false,
            //         processData: false,
            //         success: function(response) {
            //             if (response.isStatus) {
            //                 setTimeout(function() {
            //                     window.location.href = "{{ route('projects.view', ['project_id']) }}/" + response.project_id;
            //                 }, 3000);
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             console.error(xhr.responseText);
            //             // Handle error response
            //             $submitButton.html(originalText);
            //             $submitButton.prop('disabled', false);
            //         }
            //     });
            // });
        });
    </script>
@endsection
