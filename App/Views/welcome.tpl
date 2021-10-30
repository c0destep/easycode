{extends file="./Layout/Content.tpl"}

{capture name="title"}{/capture}

{capture name="header_block"}
    <link rel="stylesheet" href="{assets("potato/demo.css")}">
{/capture}

{capture name="content"}
    <div class="ptt__container">
        <header class="ptt__header">
            <nav class="ptt__navbar">
                <a class="ptt__navbar-link" href="https://github.com/phytoline/potato-framework-codingstep"
                   target="_blank">GitHub</a>
            </nav>
        </header>
        <main class="ptt__main">
            <div>
                <h1 class="ptt__title">Potato Framework <sup class="ptt__sup">powered Codingstep</sup></h1>
                <small class="ptt__subtitle">PF is a Simple Framework build with PHP 8.x</small>
            </div>
            <div>
                <a class="ptt__button" href="https://github.com/phytoline/potato-framework-codingstep/blob/main/README.md" target="_blank">
                    Documentation
                </a>
            </div>
        </main>
        <footer class="ptt__footer">
            <p>

            </p>
        </footer>
    </div>
{/capture}

{capture name="footer_block"}{/capture}