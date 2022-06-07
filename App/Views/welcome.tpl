<!DOCTYPE HTML>
<html lang="{$FastApp->getConfig("lang")}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{$FastApp->getConfig("name_project")}</title>
    <meta name="description" content="">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="shortcut icon" type="image/x-icon" href="{assets("easycode/favicon.ico")}">
    <link rel="apple-touch-icon" sizes="180x180" href="{assets("easycode/apple-touch-icon.png")}">
    <link rel="icon" type="image/png" sizes="32x32" href="{assets("easycode/favicon-32x32.png")}">
    <link rel="icon" type="image/png" sizes="16x16" href="{assets("easycode/favicon-16x16.png")}">
    <link rel="manifest" href="{assets("easycode/site.webmanifest")}">
    <link rel="stylesheet" href="{assets("easycode/demo.css")}">
</head>
<body>
<div class="ptt__container">
    <header class="ptt__header">
        <nav class="ptt__navbar">
            <a class="ptt__navbar-link" href="https://github.com/c0destep/easycode" target="_blank">GitHub</a>
        </nav>
    </header>
    <main class="ptt__main">
        <div>
            <img src="{assets('easycode/android-chrome-192x192.png')}" alt="Easycode logo">
            <h1 class="ptt__title">Easycode Framework</h1>
            <small class="ptt__subtitle">EF is a Simple Framework build with PHP 8</small>
        </div>
        <div>
            <a class="ptt__button" href="https://github.com/c0destep/easycode/blob/main/README.md" target="_blank">
                Documentation
            </a>
        </div>
    </main>
    <footer class="ptt__footer">
        <span>Version: {VERSION}</span>
    </footer>
</div>

<script src="{assets('js/app.js')}"></script>
</body>
</html>