<?php

function isAnEmptyNonZeroString(string $s): bool
{
    if ($s === '0') return false;
    return empty($s);
}
