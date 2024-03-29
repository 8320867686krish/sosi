
function triggerFileInput(inputId) {
    $(`#${inputId}`).val('');
    document.getElementById(inputId).click();
}

async function convertToImage() {
    const pdfFile = document.getElementById('pdfFile').files[0];
    if (!pdfFile) {
        alert('Please select a PDF file.');
        return;
    }

    const fileReader = new FileReader();
    fileReader.onload = async function () {
        const pdfData = new Uint8Array(this.result);
        const pdf = await pdfjsLib.getDocument({
            data: pdfData
        }).promise;

        for (let i = 1; i <= pdf.numPages; i++) {
            const page = await pdf.getPage(i);
            const viewport = page.getViewport({
                scale: 1
            });
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            await page.render({
                canvasContext: context,
                viewport
            }).promise;
            $("#img-container").empty();
            const imageData = canvas.toDataURL('image/png');
            const img = document.createElement('img');
            img.src = imageData;
            img.classList.add('pdf-image'); // Add a class to the image
            const container = document.getElementById('img-container');
            container.appendChild(img);
        }

        // Bind event listeners after images are loaded
        $('.pdf-image').on('load', function () {
            var options = {
                deleteMethod: 'doubleClick',
                handles: true,
                onSelectEnd: function (image, selection) {
                    console.log("Selection End:", selection);
                },
                initAreas: []
            };
            $(this).areaSelect(options);
        });
    };
    fileReader.readAsArrayBuffer(pdfFile);
    $('#pdfModal').modal('show');

}

function previewFile(input) {
    let file = $("input[type=file]").get(0).files[0];

    if (file) {
        let reader = new FileReader();

        reader.onload = function () {
            $("#previewImg").attr("src", reader.result);
        }

        reader.readAsDataURL(file);
    }
}

function removeInvalidClass(input) {

    const isValid = input.value.trim() !== '';

    input.classList.toggle('is-invalid', !isValid);

    const errorMessageElement = input.parentElement.querySelector('.invalid-feedback');

    if (errorMessageElement) {
        errorMessageElement.style.display = isValid ? 'none' : 'block';
    }
}

$(document).ready(function () {
    // Hide all sections initially
    $('.main-content').hide();

    // Show the default section
    $('#ship_particulars').show();

    $('#pdfFile').change(function () {
        convertToImage();
    });

    // Handle click event on sidebar menu items
    $('.aside-nav .nav li a').click(function () {
        // Remove active class from all <li> tags
        $('.aside-nav .nav li').removeClass('active');
        // Add active class to the parent <li> tag
        $(this).parent('li').addClass('active');

        // Hide all sections
        $('.main-content').hide();
        // Get the ID of the section to show
        var targetId = $(this).attr('href');
        // Show the corresponding section
        $(targetId).show();
        // Prevent default anchor behavior
        return false;
    });

    setTimeout(function () {
        $('.alert-success').fadeOut();
    }, 15000);

    $(".SurveyFormButton").click(function () {
        $('span').html("");

        $.ajax({
            type: "POST",
            url: "{{ url('detail/save') }}",
            data: $("#SurveyForm").serialize(),
            success: function (msg) {
                $(".sucessSurveylMsg").show();
            }
        });
    });

    $(".formteamButton").click(function () {
        // $('span').html("");
        $.ajax({
            type: "POST",
            url: "{{ url('detail/assignProject') }}",
            data: $("#teamsForm").serialize(),
            success: function (msg) {
                $(".sucessteamMsg").text(msg.message);
                $(".sucessteamMsg").show();
            },
            error: function (err) {
                console.log(err);
            },
            complete: function () {
                $(".formSubmitBtn").hide();
            }
        });
    });

    $(".formgenralButton").click(function () {
        $('span').html("");

        $.ajax({
            type: "POST",
            url: "{{ url('detail/save') }}",
            data: $("#genralForm").serialize(),
            success: function (msg) {
                $(".sucessgenralMsg").show();
            }
        });
    });

    $('#projectForm').submit(function (e) {
        e.preventDefault();

        // Clear previous error messages and invalid classes
        $('.error').empty().hide();
        $('input').removeClass('is-invalid');
        $('select').removeClass('is-invalid');

        var formData = new FormData(this);

        $.ajax({
            url: "{{ url('detail/save') }}",
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                // Handle success response
                $(".sucessMsg").show();
            },
            error: function (xhr, status, error) {
                // If there are errors, display them
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    // Loop through errors and display them
                    $.each(errors, function (field, messages) {
                        // Display error message for each field
                        $('#' + field + 'Error').text(messages[0]).show();
                        // Add is-invalid class to input or select field
                        $('[name="' + field + '"]').addClass('is-invalid');
                    });
                } else {
                    console.error('Error submitting form:', error);
                }
            },
        });
    });

    $('#getDeckCropImg').click(function () {
        let textareas = [];
        let areas = $('.pdf-image').areaSelect('get');
        let projectId = $(this).data('id');
        console.log(projectId);
        areas.forEach(area => {
            var input = document.getElementById(area.id);
            if (input) {
                textareas.push({
                    ...area, // Copy existing area properties
                    'text': input.value // Add 'text' key with input value
                });
            }
        });

        let areasJSON = JSON.stringify(textareas);
        let images = document.querySelectorAll('.pdf-image');

        let imageFiles = [];
        images.forEach(function (image, index) {
            // Convert the image data URL to a blob
            fetch(image.src).then(res => res.blob())
                .then(blob => {
                    // Create a new FormData object
                    var formData = new FormData();
                    formData.append('image', blob, 'page_' + (index + 1) + '.png');
                    // formData.append('_token', '{{ csrf_token() }}');
                    formData.append('project_id', projectId);
                    formData.append('areas', areasJSON);

                    $.ajax({
                        type: 'POST',
                        url: "{{ url('project/save-image') }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            $('.deckView').html();
                            $('.deckView').html(response.html);
                            $("#img-container").empty();
                            $('#pdfModal').modal().hide();
                            $('#pdfModal').removeClass('show');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            // console.log('Image saved successfully:',response);
                        },
                        error: function (xhr, status, error) {
                            console.error('Failed to save image:', error);
                        }
                    });
                });
        });
    });

    $('#pdfModal').on('hide.bs.modal', function (e) {
        $(this).find('.modal-body').empty(); // Clear modal body content
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });

});
