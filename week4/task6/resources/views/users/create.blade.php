<x-layout>
    <h1>Register</h1>
    <form method="POST" action="/users">
        @csrf
        <label for="name">
            Name
        <input
            type="text"
            name="name"
            value="{{old('name')}}"
        >

        @error('name')
            <p>{{$message}}</p>
        @enderror

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

        <label for="password_confirmation">
            Confirm Password
        <input
            type="password"
            name="password_confirmation"
        >
        
        @error('password_confirmation')
            <p>{{$message}}</p>
        @enderror

        <button type="submit">Sign Up</button>
    </form>
</x-layout>