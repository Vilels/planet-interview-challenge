{$price}
{if $available_at eq -2}
    (always available)
{else}
    (available at {$available_at|format_date:"c"})
{/if}

