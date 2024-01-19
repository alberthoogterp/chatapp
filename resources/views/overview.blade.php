<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
</head>
<body>
    <form action="{{route('logout')}}" method="POST">
        @csrf
        <input type="submit" value="Logout">
    </form>
    @foreach($serverList as $server)
    <p><a href="chatpage/{{$server->id}}">{{$server->name}}</a></p>
    @endforeach
    <form action="{{route('createServer')}}" method="GET">
        <input type="submit" value="Create new server">
    </form>
</body>
</html>