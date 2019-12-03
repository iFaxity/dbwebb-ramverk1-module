<?php

/**
 * Alias to htmlentities, escapes a string to prevent XSS injection
 * @return string escaped string.
 */
function esc(?string $str) : string
{
    return is_null($str) ? "" : htmlentities($str);
}
