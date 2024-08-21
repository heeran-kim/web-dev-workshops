<x-layout>
    <div class="d-flex justify-content-center">
        <div class="bg-light p-3 mx-3 border rounded shadow-sm" style="width: 500px;">
            <h3 class="text-center mt-3 p-1 text-uppercase">Sign Up</h3>
            <h5 class="text-center">Create your account</h5>

            <form method="POST" action="/users" enctype="multipart/form-data">
                @csrf

                <label for="name" class="form-label mt-3">Name</label>
                <input type="name" class="form-control" name="name" value="{{old('name')}}">
                @error('name')
                    <p class="text-danger">{{$message}}</p>
                @enderror

                <label for="photo" class="form-label mt-3">Photo</label>
                <input type="file" class="form-control" name="photo">

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

                <label for="password_confirmation" class="form-label mt-3">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" value="{{old('password_confirmation')}}">
                @error('password_confirmation')
                    <p class="text-danger">{{$message}}</p>
                @enderror

                <button type="submit" class="btn btn-primary m-3">Sign Up</button>
                <a href="/" class="text-decoration-none m-3">Back</a>
            </form>
            <p>
                Already have an account? <a href="/login" class="text-danger">Log in</a>
            </p>
        </div>
    </div>

    @include("partials/_footer")
</x-layout>