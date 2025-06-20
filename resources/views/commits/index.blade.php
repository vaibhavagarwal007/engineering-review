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
<h2>Recent Commits</h2>
<table>
    <thead>
        <tr>
            <th>Author</th>
            <th>Profile</th>
            <th>Message</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($commits as $commit)
            <tr>
                <td>{{ $commit['author'] }}</td>
                <td>
                    @if ($commit['profile_url'])
                        <a href="{{ $commit['profile_url'] }}" target="_blank">View Profile</a>
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $commit['message'] }}</td>
                <td>{{ \Carbon\Carbon::parse($commit['timestamp'])->toDayDateTimeString() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>