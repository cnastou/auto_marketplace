<!DOCTYPE html>
<html>
<head>
    <title>Debug Test</title>
</head>
<body>
    <h1>Transport Requests</h1>

    @foreach ($requests as $request)
        <p>Request #{{ $request->id }} - {{ $request->status }}</p>
    @endforeach
</body>
</html>
