<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div>
        {{session()->get("error") ?? ""}}
        <form action="" method="POST">
            @csrf
            <p><input type="text" name="username" placeholder="Username" required></p>
            <p><input type="password" name="password" placeholder="Password" required></p>
            <p><input type="submit" name="loginButton" value="Login"></p>
            <p><a href="/accountcreation">Create a new account</a></p>
        </form>
    </div>
</body>
</html>