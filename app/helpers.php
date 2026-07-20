<?php

if (!function_exists('filter_null')) {
    function filter_null(array $v): array
    {
        return array_filter($v, fn($val) => !is_null($val));
    }
}
