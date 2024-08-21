@extends('layouts/layout')

@section('title')
Result
@endsection

@section('content')
    @include("partials/_nav")
    
    <div class="mx-4">
        <x-card class="!max-w-7xl">
            <header class="text-center">
                <h2 class="text-2xl font-bold uppercase mb-8">Result</h2>
                <p class="mb-4">You searched for someone
                    @if (!empty($query['name']))
                        with the name <span class="font-bold text-rose-700">{{$query['name']}}</span>
                    @endif
                    @if (!empty($query['year']))
                        who served in <span class="font-bold text-rose-700">{{$query['year']}}</span>
                    @endif
                    @if (!empty($query['state']))
                        in the state of <span class="font-bold text-rose-700">{{$query['state']}}</span>
                    @endif
                </p>
            </header>
            <hr>
            @forelse ($pms as $pm)
                @if ($loop->first)
                <div class="flex justify-center w-full">
                    <table class="w-full mt-10 mx-10 border-collapse border border-gray-300 shadow-lg">
                        <tr class="bg-pms-80">
                            @foreach($pm as $item => $value)
                                <th class="p-3 uppercase text-center text-white">{{$item}}</th>
                            @endforeach
                        </tr>
                @endif
                        <tr class={{$loop->odd ? "" : "bg-pms-20"}}>
                            @foreach ($pm as $value)
                                <td class="p-3 text-center">{{$value}}</td>
                            @endforeach
                        </tr>
                @if ($loop->last)
                    </table>
                </div>
                @endif
            @empty
                <p class="text-center mt-8 text-xl">No person matching your search.</p>
            @endforelse
        </x-card>

        <div class="flex justify-center">
            <a
                href="edit"
                class="text-center border-2 border-black text-black py-2 px-4 rounded-xl uppercase mt-8 mx-2 hover:text-hello hover:border-hello"
            >
            <i class="fa fa-refresh" aria-hidden="true"></i>
             Edit Search
            </a>
            
            <a 
                href="/"
                class="text-center border-2 border-black text-black py-2 px-4 rounded-xl uppercase mt-8 mx-2 hover:text-hello hover:border-hello"
            >
            <i class="fa fa-plus" aria-hidden="true"></i>
             New Search
            </a>
        </div>
    </div>
@endsection