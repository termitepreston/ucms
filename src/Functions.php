<?php


function joinPaths(string ...$parts): string
{
    return preg_replace(
        '#/+#',
        DIRECTORY_SEPARATOR,
        implode(DIRECTORY_SEPARATOR, array_filter($parts))
    );
}
