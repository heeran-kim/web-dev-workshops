@extends('layout')

@section('title')
    Welcome
@endsection

@section('content')
    @include("partials/_nav")
    
    <div class="mx-4">
        <x-card>
            <header class="text-center">
                <h2 class="text-2xl font-bold uppercase mb-8">
                    Welcome
                </h2>
                
                <hr>

                <p class="m-8 text-xl">Hello
                    <span class="font-bold text-hello text-2xl">
                        {{session('listing')['name']}}
                    </span>
                </p>
                <p> Next year, you will be 
                    <span class="font-bold text-hello text-xl">
                        {{session('listing')['age'] + 1}}
                    </span> years old.
                </p>
            </header>
        </x-card>
        
        <div class="flex justify-center">
            <a
                href="edit"
                class="text-center border-2 border-black text-black py-2 px-4 rounded-xl uppercase mt-8 mx-2 hover:text-hello hover:border-hello"
            >
            <i class="fa fa-refresh" aria-hidden="true"></i>
             Edit your info
            </a>
            
            <a 
                href="create"
                class="text-center border-2 border-black text-black py-2 px-4 rounded-xl uppercase mt-8 mx-2 hover:text-hello hover:border-hello"
            >
            <i class="fa fa-plus" aria-hidden="true"></i>
             Create new info
            </a>
        </div>
    </div>
@endsection