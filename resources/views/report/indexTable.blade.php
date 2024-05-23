<!DOCTYPE html>
<html>
<head>
    <title>Index Table</title>
</head>
<body>
    <h1>Index Table</h1>
    <table>
        <thead>
            <tr>
                <th>Section</th>
                <th>Page Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pageNumbers as $section => $pageNumber)
                <tr>
                    <td><a href="#page={{ $pageNumber }}">{{ ucfirst($section) }}</a></td>
                    <td>{{ $pageNumber }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
