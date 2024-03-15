@extends('layouts.app')

@section('css')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap-select/css/bootstrap-select.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css') }}">
@endsection

@section('content')
    <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Project Management</h2>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-5">
                {{-- <div class="section-block">
                    <h5 class="section-title">Vertical tabs</h5>
                    <p>Takes the basic nav from above and adds the .nav-tabs class to generate a tabbed interface..</p>
                </div> --}}
                <div class="tab-vertical">
                    <ul class="nav nav-tabs" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="warfare_upsc_tab" data-toggle="tab" href="#warfare_upsc" role="tab" aria-controls="warfare" aria-selected="true">Warfare UPSC</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="onboard_survey_tab" data-toggle="tab" href="#onboard_survey"
                                role="tab" aria-controls="onboard" aria-selected="false">Onboard Survey</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="attachment_tab" data-toggle="tab" href="#attachment"
                                role="tab" aria-controls="attachment" aria-selected="false">Attachment</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="attachment_tab1" data-toggle="tab" href="#attachment1"
                                role="tab" aria-controls="attachment1" aria-selected="false">Attachment</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent3">
                        <div class="tab-pane fade show active" id="warfare_upsc" role="tabpanel"
                            aria-labelledby="warfare_upsc_tab">
                            <p class="lead"> All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks
                                as necessary, making this the first true generator on the Internet. </p>
                            <p>Phasellus non ante gravida, ultricies neque a, fermentum leo. Etiam ornare enim arcu, at
                                venenatis odio mollis quis. Mauris fermentum elementum ligula in efficitur. Aliquam id congue
                                lorem. Proin consectetur feugiasse platea dictumst. Pellentesque sed justo aliquet, posuere sem
                                nec, elementum ante.</p>
                            <a href="#" class="btn btn-secondary">Go somewhere</a>
                        </div>
                        <div class="tab-pane fade" id="onboard_survey" role="tabpanel"
                            aria-labelledby="onboard_survey_tab">
                            <h3>Tab Heading Vertical Title</h3>
                            <p>Nullam et tellus ac ligula condimentum sodales. Aenean tincidunt viverra suscipit. Maecenas id
                                molestie est, a commodo nisi. Quisque fringilla turpis nec elit eleifend vestibulum. Aliquam sed
                                purus in odio ullamcorper congue consectetur in neque. Aenean sem ex, tempor et auctor sed,
                                congue id neque. </p>
                            <p> Fusce a eros pellentesque, ultricies urna nec, consectetur dolor. Nam dapibus scelerisque risus,
                                a commodo mi tempus eu.</p>
                        </div>
                        <div class="tab-pane fade" id="attachment" role="tabpanel"
                            aria-labelledby="attachment_tab">
                            <h3>Tab Heading Vertical attachment</h3>
                            <p>Vivamus pellentesque vestibulum lectus vitae auctor. Maecenas eu sodales arcu. Fusce lobortis,
                                libero ac cursus feugiat, nibh ex ultricies tortor, id dictum massa nisl ac nisi. Fusce a eros
                                pellentesque, ultricies urna nec, consectetur dolor. Nam dapibus scelerisque risus, a commodo mi
                                tempus eu.</p>
                            <p> Fusce a eros pellentesque, ultricies urna nec, consectetur dolor. Nam dapibus scelerisque risus,
                                a commodo mi tempus eu.</p>
                        </div>
                        <div class="tab-pane fade" id="attachment1" role="tabpanel"
                            aria-labelledby="attachment_tab1">
                            <h3>Tab Heading Vertical attachment1</h3>
                            <p>Vivamus pellentesque vestibulum lectus vitae auctor. Maecenas eu sodales arcu. Fusce lobortis,
                                libero ac cursus feugiat, nibh ex ultricies tortor, id dictum massa nisl ac nisi. Fusce a eros
                                pellentesque, ultricies urna nec, consectetur dolor. Nam dapibus scelerisque risus, a commodo mi
                                tempus eu.</p>
                            <p> Fusce a eros pellentesque, ultricies urna nec, consectetur dolor. Nam dapibus scelerisque risus,
                                a commodo mi tempus eu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/vendor/bootstrap-select/js/bootstrap-select.js') }}"></script>
    <script>
        function removeInvalidClass(input) {
            // Check if the input value is empty or whitespace only
            const isValid = input.value.trim() !== '';

            // Toggle the 'is-invalid' class based on the validity
            input.classList.toggle('is-invalid', !isValid);
        }

        function previewFile(input) {
            let file = $("input[type=file]").get(0).files[0];

            if (file) {
                let reader = new FileReader();

                reader.onload = function() {
                    $("#previewImg").attr("src", reader.result);
                }

                reader.readAsDataURL(file);
            }
        }

        $(document).ready(function() {

            setTimeout(function() {
                $('.alert-success').fadeOut();
            }, 15000);

            var ship_identi = $("#client_id").find('option:selected').data('identi');
            var imo = $('#imo_number').val();

            $('#imo_number').blur(function() {
                imo = $(this).val();
                $("#project_no").val("sosi/" + ship_identi + "/" + imo);
            });
            $('#client_id').change(function() {
                ship_identi = $(this).find('option:selected').data('identi');
                $("#project_no").val("sosi/" + ship_identi + (imo ? "/" + imo : ""));
            })

            $("#projectForm").on("input", function() {
                $(".formSubmitBtn").show();
            });

            $("#teamsForm").on("input select", function() {
                $(".formteamButton").show();
            });

            $("#SurveyForm").on("input", function() {
                $(".SurveyFormButton").show();
            });
            $(".SurveyFormButton").click(function() {
                $('span').html("");

                $.ajax({
                    type: "POST",
                    url: "{{ url('detail/save') }}",
                    data: $("#SurveyForm").serialize(),
                    success: function(msg) {
                        $(".sucessSurveylMsg").show();
                    }
                });
            });

            $(".formteamButton").click(function() {
                // $('span').html("");
                $.ajax({
                    type: "POST",
                    url: "{{ url('detail/assignProject') }}",
                    data: $("#teamsForm").serialize(),
                    success: function(msg) {
                        $(".sucessteamMsg").text(msg.message);
                        $(".sucessteamMsg").show();
                    },
                    error: function(err) {
                        console.log(err);
                    },
                    complete: function() {
                        $(".formSubmitBtn").hide();
                    }
                });
            });

            $(".formgenralButton").click(function() {
                $('span').html("");

                $.ajax({
                    type: "POST",
                    url: "{{ url('detail/save') }}",
                    data: $("#genralForm").serialize(),
                    success: function(msg) {
                        $(".sucessgenralMsg").show();
                    }
                });
            });

            $('#projectForm').submit(function(e) {
                e.preventDefault();

                let $submitButton = $(this).find('button[type="submit"]');
                let originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);

                // Clear previous error messages and invalid classes
                $('.error-message').remove("");

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ url('detail/save') }}", // Change this to the URL where you handle the form submission
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success response
                        $(".sucessMsg").show();
                    },
                    error: function(xhr, status, error) {
                        // If there are errors, display them
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            // Loop through errors and display them
                            $.each(errors.responseJSON.errors, function(i, error) {
                                var el = $(document).find('[name="' + i + '"]');
                                el.after($('<span class="error-message" style="color: red;">' +
                                    error[0] + '</span>'));
                            })
                        } else {
                            console.error('Error submitting form:', error);
                        }
                    },
                    complete: function() {
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
