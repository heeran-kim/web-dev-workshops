<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
            integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'pms': "#091E42",
                            'pms-20': 'rgba(9, 30, 66, 0.2)',
                            'pms-80': 'rgba(9, 30, 66, 0.8)',
                        },
                    },
                },
            };
        </script>
        <title>Find a Prime Minister | @yield('title')</title>
    </head>
    <body class="mb-48">

        <main>
            @yield('content')
        </main>

        <footer
            class="fixed bottom-0 w-full flex items-center text-center font-bold bg-pms text-white h-24 mt-24 opacity-90 justify-center"
        >
            <p class="ml-2">Copyright &copy; 2024, Heeran Kim. All Rights reserved</p>
        </footer>
    </body>
</html>