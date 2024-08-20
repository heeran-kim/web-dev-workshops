<x-layout>
    <form method="POST" action="/users/authenticate">
        @csrf
        <label for="email">
            E-mail
        <input
            type="email"
            name="email"
            value="{{old('email')}}"
        >

        @error('email')
            <p>{{$message}}</p>
        @enderror

        <label for="password">
            Password
        <input
            type="password"
            name="password"
        >

        @error('password')
            <p>{{$message}}</p>
        @enderror

        <button type="submit">Log in</button>
    </form>
</x-layout>