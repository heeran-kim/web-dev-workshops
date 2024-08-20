<x-layout>
    <h1>Index</h1>

    @unless(count($postings) == 0)
        @foreach ($postings as $posting)
            <p>
                {{$posting->user->name}}
                {{$posting->id}}
                {{$posting->title}}
                {{$posting->updated_at->format('d/M/Y')}}
                <img
                    src="{{asset('storage/' . $posting->image)}}"
                    alt="image"
                />
                {{$posting->description}}
            </p>

            @unless(count($posting->comments) == 0)
                @foreach ($posting->comments as $comment)
                    <p>
                        {{$comment->user->name}}: {{$comment->message}}
                    </p>
                @endforeach
            @else
                <p>No comments</p>
            @endunless

            <form method="POST" action="/postings/{{$posting->id}}/comments/add">
                @csrf
                <label
                    for="message"
                >
                Comments:
                <input
                    type="text"
                    name="message"
                    value="{{old("message")}}"
                >
                @error('message')
                    <p>{{$message}}</p>
                @enderror
                <button type="submit">
                    Send
                </button>
            </form>
        @endforeach
    @else
        <p>No postings found</p>
    @endunless

    <hr>
    
    <a href="/postings/create">Create</a>
    {{-- <form method="POST" action="/{posting}/comments/add">
        @csrf
        <label
            for="message"
        >
            Comment
        </label>
        <input
            type="text"
            name="message"
        />

        <button type="submit">
            Send
        </button>
    </form> --}}
</x-layout>