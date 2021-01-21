<?php

/**
 * The route link helper lets us show
 * or hide a class based on the route.
 */
if (! function_exists('route_link_class')) {
    function route_link_class($route, $active = 'active', $class = 'nav-link')
    {
        if (request()->routeIs($route)) {
            return "{$class} {$active}";
        }

        return $class;
    }
}

/**
 * Compile the session errors into a single message.
 */
if (! function_exists('sessionErrorMessage')) {
    function sessionErrorMessage()
    {
        return str_replace("'", '`', collect(optional(session('errors'))->toArray())->flatten()->implode(' '));
    }
}
