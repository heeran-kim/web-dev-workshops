@extends('layouts/layout')

@section('title')
Search
@endsection

@section('content')
    @include('partials/_nav')
    <div class="mx-4">
        <x-card>
            <header class="text-center">
                <h2 class="text-2xl font-bold uppercase mb-1"> Explore Prime Ministers </h2>
                <p class="mb-4">You can search by name, year, or state!</p>
            </header>

            <form method="POST" action="search">
                @csrf
                
                <!-- Name input field -->
                <div class="mb-6">
                    <label for="name" class="inline-block text-lg mb-2"> Name </label>
                    <input
                        type="text"
                        class="border border-gray-200 rounded p-2 w-full"
                        name="name"
                        {{-- Retain the old input value after form submission --}}
                        value="{{old('name')}}"
                    />

                    <!-- Error message for name validation -->
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <!-- Year input field -->
                <div class="mb-6">
                    <label for="year" class="inline-block text-lg mb-2"> Year </label>
                    <input
                        type="number"
                        class="border border-gray-200 rounded p-2 w-full"
                        name="year"
                        {{-- Retain the old input value after form submission --}}
                        value="{{old('year')}}"
                    />

                    <!-- Error message for name validation -->
                    @error('year')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <!-- State input field -->
                <div class="mb-6">
                    <label for="state" class="inline-block text-lg mb-2"> State </label>
                    <input
                        type="text"
                        class="border border-gray-200 rounded p-2 w-full"
                        name="state"
                        {{-- Retain the old input value after form submission --}}
                        value="{{old('state')}}"
                    />

                    <!-- Error message for name validation -->
                    @error('state')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <!-- Submit button -->
                <div class="text-center">
                    <button type="submit" class="bg-pms text-white rounded py-2 px-4 hover:bg-black">
                        <i class="fa fa-search" aria-hidden="true"></i> Search
                    </button>
                </div>
            </form>
        </x-card>
    </div>
@endsection