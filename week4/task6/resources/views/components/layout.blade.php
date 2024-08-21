<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>MySocial</title>
</head>
<body>
    <nav class="sticky-top bg-white shadow-sm">
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
                <div class="col-6 col-md-3 col-sm-4 mb-md-0">
                    <a href="/" class="d-flex align-items-center mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                        <img src="{{asset('/images/logo.png')}}" class="bi me-2" width="40"><use xlink:href="/">
                        <span class="fs-4">My Social</span>
                    </a>
                </div>
        
                <div class="col-12 col-md-6 col-sm-4 d-none d-sm-block mb-md-0">
                    <form class="col-12 col-lg-auto mb-lg-0 me-lg-3" role="search">
                        <input type="search" class="form-control" placeholder="Search user..." aria-label="Search">
                    </form>
                </div>
        
                <div class="col-6 col-md-3 col-sm-4 mb-md-0 text-end">
                    @auth
                        {{-- <p>Welcome {{auth()->user()->name}}</p> --}}
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary me-2">logout</button>
                        </form>
                    @else
                        <a href="/login">
                        <button type="button" class="btn btn-outline-primary me-2">Login</button>
                        </a>
                        {{-- <a href="/register">Sign up</a> --}}
                    @endauth
                </div>
            </header>
        </div>
    </nav>

    <main class="d-flex flex-column" style="height:calc(100vh - 100px);">
        {{$slot}}
    </main>
</body>
</html>