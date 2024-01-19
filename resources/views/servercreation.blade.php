<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server creation</title>
</head>
<body>
    {{session()->get("error") ?? ""}}
    <form action="{{route('createServer')}}" method="POST">
        @csrf
        <input type="text" name="servername" placeholder="Server name" autocomplete="off" required>
        <input type="submit" value="Add new server">
    </form>
    <form action="{{route('overview')}}" method="GET">
        <input type="submit" value="Cancel">
    </form>
</body>
</html>