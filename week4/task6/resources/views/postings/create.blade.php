<x-layout>
    <h1>Create</h1>
    <form method="POST" action="/postings" enctype="multipart/form-data">
        @csrf
        <label
            for="title"
        >
            Title
        </label>
        <input
            type="text"
            name="title"
            value="{{old('title')}}"
        />
        @error('title')
            <p>{{$message}}</p>
        @enderror

        <label
            for="image"
        >
            Image
        </label>
        <input
            type="file"
            name="image"
        />

        <label
            for="description"
        >
            Description
        </label>
        <textarea
            name="description"
        >
        {{old('description')}}
        </textarea>
        @error('description')
            <p>{{$message}}</p>
        @enderror

        <button type="submit">
            Create
        </button>
    </form>
</x-layout>