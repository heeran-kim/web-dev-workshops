{{-- 
    Filename: greeting.blade.php
    Author: Heeran Kim
    Created Date: 2024-08-16
    Last Modified: 2024-08-17
    Description: This Blade template displays a welcome message and provides options 
                 for the user to edit their information or create new information. 
                 It uses session data to display the user's name and predicted age 
                 for the next year.

    -- Main Layout Inclusion --
    - The Blade template extends the main layout to ensure consistent structure across the application.
    - The layout includes navigation, content, and footer sections.
--}}
<x-layout>
    {{-- Include the navigation bar --}}
    @include("partials/_nav")
    
    <div class="mx-4">
         {{-- Card component to display welcome message and user information --}}
        <x-card>
            <header class="text-center">
                <h2 class="text-2xl font-bold uppercase mb-8">
                    Welcome
                </h2>
                
                <hr>

                {{-- Display the user's name and next year's age from session data --}}
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
        
        {{-- Centered buttons for editing or creating new user information --}}
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
</x-layout>