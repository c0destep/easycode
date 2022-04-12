<!DOCTYPE HTML>
<html lang="{$FastApp->getConfig("lang")}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{$FastApp->getConfig("name_project")}</title>
    <meta name="description" content="">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="shortcut icon" type="image/x-icon" href="{assets("easycode/favicon.ico")}">
    <link rel="stylesheet" href="{assets("css/output.css")}">
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
            <h1 class="ptt__title">Easycode Framework</h1>
            <small class="ptt__subtitle">PF is a Simple Framework build with PHP 8.x</small>
        </div>
        <div>
            <a class="ptt__button"
               href="https://github.com/c0destep/easycode/blob/main/README.md" target="_blank">
                Documentation
            </a>
        </div>
    </main>
    <footer class="ptt__footer">
        {if isset($Id)}
            <h4>User</h4>
            <p>
                Parameter <strong>ID</strong>
                <br>
                Value: {$Id}
            </p>
            <p>
                Parameter <strong>Name</strong>
                <br>
                Value: {$Name}
            </p>
            <p>
                Parameter <strong>E-mail</strong>
                <br>
                Value: {$Email}
            </p>
        {/if}
    </footer>
</div>

<script src="{assets('js/vendor/modernizr-3.11.2.min.js')}"></script>
<script src="{assets('js/app.js')}"></script>
</body>
</html>