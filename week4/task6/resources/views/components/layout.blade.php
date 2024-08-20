<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MySocial</title>
</head>
<body>
    <nav>
        <a href="/">Home</a>
        @auth
            <p>Welcome {{auth()->user()->name}}</p>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit">logout</button>
            </form>
        @else
            <a href="/login">Log in</a>
            <a href="/register">Sign up</a>
        @endauth
    </nav>
    <main>
        {{$slot}}
    </main>
</body>
</html>