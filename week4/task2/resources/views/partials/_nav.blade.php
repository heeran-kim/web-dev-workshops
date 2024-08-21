<!-- 
    Filename: navigation.blade.php
    Author: Heeran Kim
    Created Date: 2024-08-15
    Last Modified: 2024-08-16
    Description: This file contains the HTML and Blade template code for the navigation bar. 
                 The navigation bar includes a centered logo that links to the homepage.
-->

<!-- Navigation bar with centered logo -->
<nav class="flex justify-center items-center bg-hello">
    <!-- Link that directs users to the homepage -->
    <a href='/'>
        <!-- 
            Logo image centered within the navigation bar 
            - `asset('images/logo.png')`: This helper function generates the correct URL for the logo image.
              It ensures that the path to the image is relative to the `public` directory of the Laravel application.
        -->
        <img src={{asset('images/logo.png')}} class="w-52">
    </a>
</nav>