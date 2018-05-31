<?php

function isActiveRoute($route, $output = 'active')
{
    $currentRoute = Route::currentRouteName();
    if ((is_array($route) && in_array($currentRoute, $route)) || $currentRoute == $route) {
        return $output;
    }
    return '';
}
