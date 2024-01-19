<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account creation</title>
</head>
<body>
    <span>{{session()->get("error") ?? ""}}</span>
    <form action="" method="POST">
        @csrf
        <p><input type="email" name="email" required autocomplete="off" placeholder="E-mail"></p>
        <p><input type="text" name="username" required autocomplete="off" placeholder="Username"></p>
        <p><input type="password" name="password" required autocomplete="off" placeholder="Password"></p>
        <p><input type="password" name="passwordConfirm" required autocomplete="off" placeholder="Confirm password"></p>
        <p><input type="submit" name="createButton" value="Create account"></p>
    </form>
    <form action="/login" method="get">
        <input type="submit" value="Back">
    </form>
</body>
</html>