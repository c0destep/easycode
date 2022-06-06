<!DOCTYPE HTML>
<html lang="{$FastApp->getConfig("lang")}" class="antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{$FastApp->getConfig("name_project")}</title>
    <meta name="description" content="...">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>

    <meta property="og:title" content="{$FastApp->getConfig("name_project")}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{route()}">
    <meta property="og:image" content="...">

    <link rel="manifest" href="...">
    <link rel="shortcut icon" type="image/x-icon" href="{assets("easycode/favicon.ico")}">
    <link rel="apple-touch-icon" href="{assets("easycode/favicon.ico")}">

    <link rel="stylesheet" href="{assets("css/output.css")}">

    <meta name="theme-color" content="#F59E0B">
</head>
<body class="bg-zinc-50 dark:bg-zinc-900 rounded-sm">
<div class="container mx-auto">
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
                    Sorry, we couldn't find the page you're looking for.
                </p>
                <a href="{route()}" class="text-xl font-bold text-amber-600 hover:text-amber-700">
                    Go back home
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>