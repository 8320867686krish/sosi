<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Concept - Bootstrap 4 Admin Dashboard Template</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="../../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/libs/css/style.css">
    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/cropper/dist/cropper.min.css">
    <style>
        #pdf-container {
            position: relative;
            width: 100%;
            overflow: auto;
        }

        #pdf-page {
            display: block;
            margin: auto;
        }

        #cropper-container {
            position: relative;
            width: 100%;
            overflow: hidden;
        }
    </style>
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->

        <div class="container-fluid dashboard-content">
            <input type="file" id="pdfFile" accept=".pdf">
            <button onclick="convertToImage()">Convert to Image</button>
            <!-- ============================================================== -->
            <!-- pageheader -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-header">
                        <h2 class="pageheader-title">Cropper</h2>
                        <p class="pageheader-text">Proin placerat ante duiullam scelerisque a velit ac porta, fusce sit amet vestibulum mi. Morbi lobortis pulvinar quam.</p>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Cropper</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end pageheader -->
            <!-- ============================================================== -->

            <div class="row">

                <!-- ============================================================== -->
                <!-- cropper  -->
                <!-- ============================================================== -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-9">
                                    <!-- <h3>Demo:</h3> -->
                                    <div id="img-container">

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- end cropper  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- end footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="../../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="../../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="../../assets/libs/js/main-js.js"></script>
    <script src="../../assets/vendor/cropper/dist/cropper.min.js"></script>
    <script src="../../assets/vendor/cropper/dist/cropper-int.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script>
        async function convertToImage() {
            const pdfFile = document.getElementById('pdfFile').files[0];
            if (!pdfFile) {
                alert('Please select a PDF file.');
                return;
            }

            const fileReader = new FileReader();
            fileReader.onload = async function() {
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

                    const imageData = canvas.toDataURL('image/png');
                    const img = document.createElement('img');
                    img.src = imageData;

                    const container = document.getElementById('img-container');
                    container.appendChild(img);
                    const aspectRatio = viewport.width / viewport.height;

                    // Adjust container size if necessary
                    container.style.width = viewport.width + 'px';
                    container.style.height = viewport.height + 'px';

                    // Initialize Cropper.js on the image
                    const cropper = new Cropper(img, {
                        aspectRatio: NaN, // Allow free cropping
                        viewMode: 1, // Display the cropped area in the container
                        autoCropArea: 1, // Show the entire image initially
                        zoomable: false, // Disable zooming
                        scalable: false,

                    });
                }
            };
            fileReader.readAsArrayBuffer(pdfFile);
        }

        function DebugCrop() {

        }
    </script>
</body>


</html>