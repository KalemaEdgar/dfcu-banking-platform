<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>DFCU Payments Gateway</title>
</head>

<body class="bg-gray-50">
    <div class="min-h-full">
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="http://dfcu-banking-platform.test/images/logo.jpeg"
                                alt="DFCU">
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                                <a href="/accounts/"
                                    class="bg-gray-900 text-white hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                                    aria-current="page">Accounts</a>
                                <a href="/payment/"
                                    class="bg-gray-900 text-white hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Payments</a>
                            </div>
                        </div>
                    </div>

                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">

                            <!-- Profile dropdown -->
                            <div class="relative ml-3">
                                <div class="flex items-center">
                                    <button type="button"
                                        class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <img class="h-10 w-10 rounded-full"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                            alt="">
                                    </button>

                                    <form class="block leading-tight ml-4">
                                        <p class="text-white text-sm font-medium">
                                            {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</p>
                                        <p class="text-white text-sm font-sm">
                                            {{ Auth::user()->email }} &nbsp; | &nbsp;
                                            <a href="/logout"
                                                class="text-white hover:text-blue-700 text-sm"><i>Logout</i></a>
                                        </p>
                                        {{-- <button class="text-white text-xs">Log out</button> --}}
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </nav>

        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    @yield('title')
                </h1>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-full py-6 sm:px-6 lg:px-8">
                <!-- Replace with your content -->
                <div class="px-4 py-6 sm:px-0">
                    @yield('content')
                </div>
                <!-- /End replace -->
            </div>
        </main>
    </div>
</body>

</html>
