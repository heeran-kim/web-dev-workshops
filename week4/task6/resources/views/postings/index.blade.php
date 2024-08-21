<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('[id^="toggle-comments"]').forEach(function(linkText) {
            let collapseElement = document.querySelector(linkText.getAttribute('href'));

            if (linkText && collapseElement) {
                linkText.addEventListener('click', function () {
                    if (collapseElement.classList.contains('show')) {
                        linkText.innerText = 'View all comments';
                    } else {
                        linkText.innerText = 'Hide comments';
                    }
                });

                collapseElement.addEventListener('shown.bs.collapse', function () {
                    linkText.innerText = 'Hide comments';
                });

                collapseElement.addEventListener('hidden.bs.collapse', function () {
                    linkText.innerText = 'View all comments';
                });
            }
        });
    });
</script>

<x-layout>
    <div class="container">
        <div class="row">
            {{-- sidebar column --}}
            <div class="col-md-4 col-lg-3 d-none d-md-block">
                <div
                    class="position-sticky bg-light p-3 border rounded shadow-sm"
                    style="height: calc(100vh - 130px); top: 100px;"
                >
                    {{-- user info --}}
                    <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto">
                        @auth
                        <img
                            src="{{auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('images/no-image.png')}}"
                            class="rounded-circle border me-3"
                            width="60"
                            height="60"
                            style="object-fit: cover;"
                        >
                        <div class="text-truncate">
                            <span class="fs-5 fw-bold d-block">{{auth()->user()->name}}</span>
                            <span class="fw-light d-block">{{auth()->user()->email}}</span>
                        </div>
                        @else
                        <div class="text-truncate">
                            <span class="fs-5 fw-bold d-block">Hello Guest</span>
                            <span class="fw-light d-block"><a href="/login" class="text-black">Login</a> please</span>
                        </div>
                        @endauth
                    </div>

                    <hr>

                    {{-- menu --}}
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item mb-3">
                            <a href="/" class="nav-link" aria-current="page">
                                <i class="bi bi-house me-2" width="16" height="16"></i>
                                Home
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a href="/" class="nav-link" aria-current="page">
                                <i class="bi bi-envelope me-2" width="16" height="16"></i>
                                Messages
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a href="/" class="nav-link" aria-current="page">
                                <i class="bi bi-bell me-2" width="16" height="16"></i>
                                Notifications
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a href="/" class="nav-link" aria-current="page">
                                <i class="bi bi-bookmark me-2" width="16" height="16"></i>
                                Bookmarks
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a href="/" class="nav-link" aria-current="page">
                                <i class="bi bi-gear me-2" width="16" height="16"></i>
                                Settings
                            </a>
                        </li>
                    </ul>

                    {{-- create post --}}
                    <a href="/postings/create" class="d-grid mt-3">
                        <button class="btn btn-primary" type="button">Create Posting</button>
                    </a>
                </div>
            </div>
    
            {{-- center column --}}
            <div class="col-12 col-md-8 col-lg-6">
                @unless(count($postings) == 0)
                @foreach ($postings as $posting)

                {{-- posting card --}}
                <div id="posting-{{$posting->id}}" class="border rounded mb-4 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <img
                                src="{{$posting->user->photo ? asset('storage/' . $posting->user->photo) : asset('images/no-image.png')}}"
                                class="rounded-circle border me-1"
                                width="30" height="30" alt="user"
                                style="object-fit: cover;"
                            >
                            <strong class="d-inline-block mb-2 text-primary-emphasis">{{$posting->title}}</strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="text-body-secondary">{{$posting->updated_at->format('d/M/Y')}}</small>
                            @auth
                                @if (auth()->user()->id == $posting->user->id)
                                    <form method="POST" action="/postings/{{$posting->id}}" class="mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-close"></button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                    <img
                        src="{{asset('storage/' . $posting->image)}}" alt="img"
                        class="card-img object-fit-cover border rounded"
                        style="height: 400px;"
                    >
                    <div class="my-3">
                        <strong>{{$posting->user->name}}</strong>
                        <span class="card-text">{{$posting->description}}</span>
                    </div>
                    
                    {{-- comment card --}}
                    <div class="border rounded shadow-sm p-4">
                        @unless(count($posting->comments) == 0)
                            @foreach ($posting->comments as $comment)
                                @if ($loop->index < 2)
                                    <p><span class="fw-bold">{{$comment->user->name}}</span> {{$comment->message}}</p>
                                @else
                                    @if ($loop-> index == 2)
                                        <p class="d-inline-flex gap-1">
                                            <a
                                                class="text-decoration-none text-muted fw-lighter"
                                                data-bs-toggle="collapse"
                                                href="#collapse{{$posting->id}}"
                                                id="toggle-comments-{{$posting->id}}"
                                            >
                                                View all comments
                                            </a>
                                        </p>
                                    @endif
                                    <div class="collapse" id="collapse{{$posting->id}}">
                                        <p><span class="fw-bold">{{$comment->user->name}}</span> {{$comment->message}}</p>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <p class="text-muted fw-lighter">No comments</p>
                        @endunless

                        <form
                            method="POST"
                            action="/postings/{{$posting->id}}/comments/add"
                            class="d-flex align-items-top"
                        >
                            @csrf
                            <img
                                @auth
                                src="{{auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('images/no-image.png')}}"
                                @else
                                src="{{asset('images/no-image.png')}}"
                                @endauth
                                class="rounded-circle border me-1"
                                width="30" height="30" alt="user"
                                style="object-fit: cover;"
                            >
                            <div class="flex-grow-1 me-1">
                            @auth
                                <input
                                    class="rounded-pill w-100 border px-2"
                                    style="height: 30px;"
                                    type="text"
                                    name="message"
                                    placeholder="Add a comment for {{$posting->user->name}}..."
                                    value="{{old("message")}}"
                                >
                            @else
                                <fieldset disabled="">
                                    <input
                                        class="rounded-pill w-100 border px-2"
                                        style="height: 30px;"
                                        type="text"
                                        placeholder="You should log in first..."
                                    >
                                </fieldset>
                            @endauth
                            @error('message')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                            </div>
                            <div><button type="submit" class="border-0 bg-white"><i class="bi bi-send"></i></button></div>
                        </form>
                    </div>
                </div>
                @endforeach

                @else
                <p>No postings found</p>
                @endunless

                @include("partials/_footer")
            </div>
    
            {{-- recent activity column --}}
            @auth
            <div class="col-lg-3 d-none d-lg-block">
                <div class="position-sticky p-3" style="height: calc(100vh - 130px); top: 100px;">
                    <div>
                        <h4 class="fst-italic pb-3 border-bottom border-2">Your recent posts</h4>
                        <ul class="list-unstyled">
                            @unless (count($postings) == 0)
                            @foreach ($postings as $posting)
                                @if ($posting->user_id == auth()->user()->id)
                                    <li>
                                        <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none" href="#posting-{{$posting->id}}">
                                            {{$posting->title}} on {{$posting->updated_at->format('d/M/Y')}}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                            @else
                                <li>no postings</li>
                            @endunless
                      </ul>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </div>
</x-layout>