<x-layout>
    <div class="d-flex justify-content-center">
        <div class="bg-light p-3 mx-3 border rounded shadow-sm" style="width: 500px;">
            <h3 class="text-center mt-3 p-1 text-uppercase">Log in</h3>
            <h5 class="text-center">Enter your email and password</h5>

            <form method="POST" action="/users/authenticate">
                @csrf

                <label for="email" class="form-label mt-3">E-mail</label>
                <input type="email" class="form-control" name="email" value="{{old('email')}}">
                @error('email')
                    <p class="text-danger">{{$message}}</p>
                @enderror

                <label for="password" class="form-label mt-3">Password</label>
                <input type="password" class="form-control" name="password" value="{{old('password')}}">
                @error('password')
                    <p class="text-danger">{{$message}}</p>
                @enderror

                <button type="submit" class="btn btn-primary m-3">Log in</button>
                <a href="/" class="text-decoration-none m-3">Back</a>
            </form>
            <p>
                Don't have an account? <a href="/register" class="text-danger">Sign up</a>
            </p>
        </div>
    </div>

    @include("partials/_footer")
</x-layout>