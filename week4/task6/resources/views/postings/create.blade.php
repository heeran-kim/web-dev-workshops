<x-layout>
    <div class="d-flex justify-content-center">
        <div class="bg-light p-3 mx-3 border rounded shadow-sm" style="width: 500px;">
            <h3 class="text-center mt-3 p-1 text-uppercase">Create Posting</h3>
            <h5 class="text-center">What's on your mind today?</h5>

            <form method="POST" action="/postings" enctype="multipart/form-data">
                @csrf

                <label for="title" class="form-label mt-3">Title</label>
                <input type="text" class="form-control" name="title" value="{{old('title')}}">
                @error('title')
                    <p class="text-danger">{{$message}}</p>
                @enderror

                <label for="image" class="form-label mt-3">Image</label>
                <input type="file" class="form-control" name="image">

                <label for="description" class="form-label mt-3">Description</label>
                <textarea class="form-control" name="description" rows="5">{{old('description')}}</textarea>
                @error('description')
                    <p class="text-danger">{{$message}}</p>
                @enderror

                <button type="submit" class="btn btn-primary m-3">Create</button>
                <a href="/" class="text-decoration-none m-3">Back</a>
            </form>
        </div>
    </div>

    @include("partials/_footer")
</x-layout>