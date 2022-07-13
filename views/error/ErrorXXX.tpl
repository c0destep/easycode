<!DOCTYPE HTML>
<html lang="{$FastApp::environment('DEFAULT_LANGUAGE')}">
<head>
    <meta charset="utf-8">
    <title>{$FastApp::environment('APP_NAME')}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="apple-touch-icon" sizes="180x180" href="{assets('apple-touch-icon.png')}">
    <link rel="icon" type="image/png" sizes="32x32" href="{assets('favicon-32x32.png')}">
    <link rel="icon" type="image/png" sizes="16x16" href="{assets('favicon-16x16.png')}">
    <link rel="icon" type="image/png" sizes="any" href="{assets('favicon.ico')}">
    <link rel="manifest" href="{assets('site.webmanifest')}">
    <link rel="mask-icon" href="{assets('safari-pinned-tab.svg')}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#f0f1fe">
    <meta name="theme-color" content="#222222">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <style>
        :root {
            --code__primary: #4655f8;
            --code__dark: #222;
            --code__light: #f0f1fe;
            --code__font-family: 'Open Sans', sans-serif;
            --code__font-size: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            color: inherit;
        }

        html {
            text-rendering: optimizeLegibility;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--code__dark);
            color: var(--code__light);
            font-family: var(--code__font-family);
            font-size: var(--code__font-size);
            font-weight: 400;
            line-height: 1;
            overflow: hidden;
        }

        .code__container {
            width: 100%;
            max-width: 64rem;
            height: 100%;
            min-height: 100vh;
            margin: 0 auto;
            padding: 1rem;
        }

        .code__header,
        .code__main,
        .code__footer {
            display: block;
            width: 100%;
            text-align: center;
            padding: 1rem;
        }

        .code__header {
            height: auto;
            max-height: 15vh;
        }

        .code__main {
            display: flex;
            flex-flow: column wrap;
            justify-content: space-around;
            align-items: center;
            height: 50vh;
        }

        .code__footer {
            height: auto;
            max-height: 10vh;
        }

        .code__navbar {
            display: flex;
            flex-flow: row wrap;
            justify-content: space-between;
            align-items: center;
        }

        .code__navbar-link {
            text-decoration: none;
        }

        .code__navbar-link:hover {
            text-decoration: underline;
        }

        .code__title {
            position: relative;
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: .3rem;
            margin: 0 0 .5rem 0;
        }

        .code__sup {
            position: absolute;
            top: -1rem;
            width: max-content;
            font-size: 1.25rem;
            font-weight: 300;
            letter-spacing: normal;
        }

        .code__subtitle {
            font-size: 1rem;
            font-weight: 300;
            letter-spacing: .1rem;
        }

        .code__button {
            display: inline-flex;
            flex-flow: row nowrap;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            font-size: 1.25rem;
            font-weight: 500;
            text-transform: uppercase;
            padding: .75rem 1.25rem;
            background-color: var(--code__primary);
            border: 2px solid rgba(255, 255, 255, .1);
            cursor: pointer;
            appearance: none;
            outline: none;
        }

        .code__table {
            text-align: left;
        }

        table, tr, td {
            border: 1px solid;
            border-collapse: collapse;
            padding: 1rem;
        }
    </style>
</head>
<body>
<div class="ptt__container">
    <main class="ptt__main">
        <div>
            <h1 class="ptt__title">ERROR {$Exception->getCode()}</h1>
            <small class="ptt__subtitle">{nl2br($Exception->getMessage())}</small>
        </div>
        <table class="ptt__table">
            <tr>
                <td>{$Lang->line("handler_error_file")}</td>
                <td>{$Exception->getFile()}</td>
            </tr>
            <tr>
                <td>{$Lang->line("handler_error_line")}</td>
                <td>{$Exception->getLine()}</td>
            </tr>
        </table>
        <div>
            <a class="ptt__button" href="#">
                Go back home
            </a>
        </div>
    </main>
</div>

</body>
</html>