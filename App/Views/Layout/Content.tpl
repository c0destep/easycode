{include file="Layout/Header.tpl"}

{if $smarty.capture.header_title}

    {if $smarty.capture.icon_header}
        {$smarty.capture.icon_header}
    {else}
        {$smarty.capture.logo_menu}
    {/if}

    {$smarty.capture.header_title_top}
    {$smarty.capture.header_title}

{/if}

{if $smarty.capture.content}

    {getFlashError()}
    {getFlashSuccess()}
    {getFlashWarning()}
    
    {$smarty.capture.content}

{/if}

{include file="Layout/Footer.tpl"}