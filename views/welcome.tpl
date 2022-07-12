<!DOCTYPE HTML>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{$FastApp::environment('APP_NAME')}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <style>
        body {
            background-color: #222222;
            color: #dddddd;
            font-family: Arial, sans-serif;
        }
    </style>
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
            <h1 class="ptt__title">Easycode Framework</h1>
            <small class="ptt__subtitle">{__('easycode')}</small>
        </div>
        <div>
            <a class="ptt__button" href="https://github.com/c0destep/easycode/blob/main/README.md" target="_blank">
                Documentation
            </a>
        </div>
    </main>
    <footer class="ptt__footer">
        <span>{__('version')}: {$FastApp::getInstance()->getVersion()}</span>
    </footer>
</div>

</body>
</html>