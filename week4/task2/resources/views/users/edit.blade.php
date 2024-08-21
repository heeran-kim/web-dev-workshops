<x-layout>
    @include('partials/_nav')
    <div class="mx-4">
        <x-card>
            <header class="text-center">
                <h2 class="text-2xl font-bold uppercase mb-1">
                    Edit your information
                </h2>
                <p class="mb-4">Edit your name and age</p>
            </header>

            <form method="POST" action="update">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="name" class="inline-block text-lg mb-2">
                        Name
                    </label>
                    <input
                        type="text"
                        class="border border-gray-200 rounded p-2 w-full"
                        name="name"
                        value="{{old('name', session('listing')['name'] ?? '')}}"
                    />

                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label
                        for="Age"
                        class="inline-block text-lg mb-2"
                    >
                        Age
                    </label>
                    <input
                        type="number"
                        class="border border-gray-200 rounded p-2 w-full"
                        name="age"
                        value="{{old('age', session('listing')['age'] ?? '')}}"
                    />

                    @error('age')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-6 text-center">
                    <button
                        type="submit"
                        class="bg-hello text-white rounded py-2 px-4 hover:bg-black"
                    >
                        Update
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-layout>