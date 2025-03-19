<!DOCTYPE html>
<html>
<head>
    <title>Show Data</title>
</head>
<body>
    <h1>Query Data</h1>
    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>Market ID</th>
                <th>Session</th>
                <th>Bid Date</th>
                <th>Open Panna</th>
                <th>Open Digit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($query as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->market_id }}</td>
                    <td>{{ $row->session }}</td>
                    <td>{{ $row->bid_date }}</td>
                    <td>{{ $row->open_panna }}</td>
                    <td>{{ $row->open_digit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h1>Data with Row Index</h1>
    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>Market ID</th>
                <th>Session</th>
                <th>Bid Date</th>
                <th>Open Panna</th>
                <th>Open Digit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->DT_RowIndex }}</td>
                    <td>{{ $row->market_id }}</td>
                    <td>{{ $row->session }}</td>
                    <td>{{ $row->bid_date }}</td>
                    <td>{{ $row->open_panna }}</td>
                    <td>{{ $row->open_digit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
