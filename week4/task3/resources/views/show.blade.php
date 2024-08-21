{{-- 
    Filename: show.blade.php
    Author: Heeran Kim
    Created Date: 2024-08-17
    Last Modified: 2024-08-17
    Description: 
--}}
<x-layout>
    @include("partials/_nav")
    
    <div class="mx-4">
        <x-card>
            <header class="text-center">
                <h2 class="text-2xl font-bold uppercase mb-8">
                    Result
                </h2>
            </header>
            <hr>
                <div class="flex justify-center w-full">
                    <table class="w-full m-10 border-collapse border border-gray-300 shadow-lg">
                        <tr class="bg-hello-80">
                            <th class="p-3 uppercase text-center text-white">Item</th>
                            <th class="p-3 uppercase text-center text-white">Value</th>
                        </tr>
                            @forelse ($getValues as $item => $value)
                                <tr class={{$loop->odd ? "" : "bg-hello-20"}}>
                                    <td class="p-3 text-center">{{$item}}</td>
                                    <td class="p-3 text-center">{{$value}}</td>
                                </tr>
                            @empty
                                <tr><td colspan=2 class="p-3 text-center">No Value</tr></td>
                            @endforelse
                    </table>
                </div>
        </x-card>
    </div>
</x-layout>