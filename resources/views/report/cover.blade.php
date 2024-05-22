<html>

<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <div style="text-align: center;">
        @php
        $logo = file_get_contents(asset('assets/images/logo.png'));

        // Encode the image data to base64
        $base64EncodedImageData = base64_encode($logo);
        @endphp
        <img src="data:image/jpeg;base64,{{ $base64EncodedImageData }}">
        <h1 style="padding-top: 50px;">Asbestos Abatement Report</h1><br />
        <h2 style="padding-top: 1px;">Report No: SAN/9442392/20240219</h2>
        <h3 style="padding-top: 1px;">SHIP NAME- MV SAFEEN AL NOUR</h3>
        <h3 style="padding-top: 1px;">IMO No: 9442392</h3>
        @php
        $imageData = file_get_contents(asset('images/projects/1/171411828287.jpg'));

        // Encode the image data to base64
        $base64EncodedImageData = base64_encode($imageData);
        @endphp

        <!-- Output the base64 encoded image data -->
        <div style="padding-top: 50px;">
            <img src="data:image/jpeg;base64,{{ $base64EncodedImageData }}" alt="Your Image">

        </div>
    </div>
    <div style="text-align: right;">
        <p>Revision: 1</p>
        <p>Prepared & Internally Audited by SOS INDIA Pvt. Ltd.</p>
    </div>
    <div style="pading-top:30px">
        <h3>According to:</h3>
        <ul>
            <li>Hong Kong International Convention for the Safe and Environmentally Sound Recycling of
                Ships (SR/CONF 45)</li>
            <li>Hong Kong International Convention for the Safe and Environmentally Sound Recycling of
                Ships (SR/CONF 45)</li>
        </ul>
    </div>

    <!-- Add additional HTML content or Blade directives here -->
</body>

</html>