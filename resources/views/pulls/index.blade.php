<!DOCTYPE html>
<html>
<head>
    <title>GitHub Pull Requests</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px 12px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h2>Pull Requests for Repo: {{ $repo }}</h2>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Created At</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pullRequests as $pr)
                <tr>
                    <td>{{ $pr['title'] }}</td>
                    <td>{{ $pr['user']['login'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($pr['created_at'])->diffForHumans() }}</td>
                    <td><a href="{{ $pr['html_url'] }}" target="_blank">View</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
