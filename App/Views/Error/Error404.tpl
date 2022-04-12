<!DOCTYPE HTML>
<html lang="{$FastApp->getConfig("lang")}" class="antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{$FastApp->getConfig("name_project")}</title>
    <meta name="description" content="CT Expresso">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>

    <meta property="og:title" content="{$FastApp->getConfig("name_project")}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{route()}">
    <meta property="og:image" content="{assets("Assets/medias/icon.png")}">

    <link rel="manifest" href="{assets("Assets/site.webmanifest")}">
    <link rel="shortcut icon" type="image/x-icon" href="{assets("Assets/medias/favicon.ico")}">
    <link rel="apple-touch-icon" href="{assets("Assets/medias/icon.png")}">

    <link rel="stylesheet" href="{assets("Assets/css/output.css")}">

    <meta name="theme-color" content="#F59E0B">
</head>
<body class="bg-zinc-50 dark:bg-zinc-900 rounded-sm">
<div class="container mx-auto">
    <div class="relative overflow-hidden">
        <div class="mx-auto">
            <div class="relative bg-zinc-50 dark:bg-zinc-900 z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-zinc-50 dark:text-zinc-900 transform translate-x-1/2"
                     fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100"/>
                </svg>
                <div>
                    <div class="relative pt-6 px-4 sm:px-6 lg:px-8">
                        <nav class="relative flex items-center justify-between sm:h-10 lg:justify-start"
                             aria-label="Global">
                            <div class="flex items-center flex-grow flex-shrink-0 lg:flex-grow-0">
                                <div class="flex items-center justify-between w-full md:w-auto">
                                    <a href="#">
                                        <span class="sr-only">
                                            {$FastApp->getConfig("name_project")}
                                        </span>
                                        <img class="h-8 w-auto sm:h-10"
                                             src="{assets('Assets/medias/icon.png')}"
                                             alt="{$FastApp->getConfig("name_project")}">
                                    </a>
                                    <div class="-mr-2 flex items-center md:hidden">
                                        <button type="button"
                                                class="bg-zinc-50 dark:bg-zinc-900 rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                                                aria-expanded="false">
                                            <span class="sr-only">
                                                Open main menu
                                            </span>
                                            <!-- Heroicon name: outline/menu -->
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 6h16M4 12h16M4 18h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden md:block md:ml-10 md:pr-4 md:space-x-5">
                                <a href="#"
                                   class="font-medium text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200">
                                    Empresa
                                </a>
                                <a href="#"
                                   class="font-medium text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200">
                                    Notícias
                                </a>
                                <a href="#"
                                   class="font-medium text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200">
                                    Horários
                                </a>
                                <a href="#"
                                   class="font-medium text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200">
                                    Vale Transporte
                                </a>
                                <a href="#" class="font-medium text-amber-500 hover:text-amber-600">
                                    Seja Colaborador
                                </a>
                            </div>
                        </nav>
                    </div>

                    <!--
                      Mobile menu, show/hide based on menu open state.

                      Entering: "duration-150 ease-out"
                        From: "opacity-0 scale-95"
                        To: "opacity-100 scale-100"
                      Leaving: "duration-100 ease-in"
                        From: "opacity-100 scale-100"
                        To: "opacity-0 scale-95"
                    -->
                    <div class="absolute z-10 top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">
                        <div class="rounded-lg shadow-md dark:shadow-none bg-zinc-50 dark:bg-zinc-800 ring-1 ring-black ring-opacity-5 overflow-hidden">
                            <div class="px-5 pt-4 flex items-center justify-between">
                                <div>
                                    <img class="h-8 w-auto"
                                         src="{assets('Assets/medias/icon.png')}"
                                         alt="{$FastApp->getConfig("name_project")}">
                                </div>
                                <div class="-mr-2">
                                    <button type="button"
                                            class="bg-zinc-50 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-500 hover:text-zinc-600 dark:text-zinc-400 dark:hover:text-zinc-300 rounded-md p-2 inline-flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-inset focus:ring-amber-500">
                                        <span class="sr-only">
                                            Close main menu
                                        </span>
                                        <!-- Heroicon name: outline/x -->
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="px-2 pt-2 pb-3 space-y-1">
                                <a href="#"
                                   class="block px-3 py-2 rounded-md text-base font-medium text-zinc-700 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700">
                                    Product
                                </a>

                                <a href="#"
                                   class="block px-3 py-2 rounded-md text-base font-medium text-zinc-700 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700">
                                    Features
                                </a>

                                <a href="#"
                                   class="block px-3 py-2 rounded-md text-base font-medium text-zinc-700 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700">
                                    Marketplace
                                </a>

                                <a href="#"
                                   class="block px-3 py-2 rounded-md text-base font-medium text-zinc-700 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 hover:bg-zinc-200 dark:hover:bg-zinc-700">
                                    Company
                                </a>
                            </div>
                            <a href="#"
                               class="block w-full px-5 py-3 text-center font-medium text-amber-600 hover:bg-zinc-200 dark:hover:bg-zinc-700">
                                Log in
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-24 h-96">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div>
                <h2 class="text-base text-amber-600 font-semibold tracking-wide uppercase">
                    404 ERROR
                </h2>
                <p class="mt-2 text-3xl sm:text-6xl font-extrabold tracking-tight text-zinc-800 dark:text-zinc-200">
                    Page not found
                </p>
                <p class="my-4 text-2xl text-zinc-500 lg:mx-auto">
                    Sorry,we coundn'tfind the page you're looking for.
                </p>
                <a href="{route()}" class="text-xl font-bold text-amber-600 hover:text-amber-700">
                    Go back home
                </a>
            </div>
        </div>
    </div>

    <footer>
        <div class="flex justify-center items-center gap-x-8 mb-4">
            <a href="#"
               class="text-base text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200">
                Link 1
            </a>
            <a href="#"
               class="text-base text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200">
                Link 2
            </a>
            <a href="#"
               class="text-base text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200">
                Link 3
            </a>
            <a href="#"
               class="text-base text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200">
                Link 4
            </a>
            <a href="#"
               class="text-base text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200">
                Link 5
            </a>
            <a href="#"
               class="text-base text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200">
                Link 6
            </a>
        </div>
        <div class="flex justify-center items-center gap-x-6 mb-4">
            <div class="text-base text-zinc-500">Icon 1</div>
            <div class="text-base text-zinc-500">Icon 2</div>
            <div class="text-base text-zinc-500">Icon 3</div>
            <div class="text-base text-zinc-500">Icon 4</div>
            <div class="text-base text-zinc-500">Icon 5</div>
            <div class="text-base text-zinc-500">Icon 6</div>
        </div>
        <div class="flex justify-center items-center">
            <span class="text-base text-zinc-500 dark:text-zinc-500">All rights reserved.</span>
        </div>
    </footer>
</div>
</body>
</html>