@if(session()->has('flashMessage'))
    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 1000)"
        x-show="show"
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 bg-hello text-white px-48 py-3">
        <p>
            {{session('flashMessage')}}
        </p>
    </div>
@endif