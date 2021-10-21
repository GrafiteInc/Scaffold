<?php

/**
 * Compile the session errors into a single message.
 */
if (! function_exists('session_error_message')) {
    function session_error_message()
    {
        $errors = session('errors', []);

        if (is_array($errors)) {
            $errors = collect($errors);
        }

        if (is_object($errors)) {
            $errors = collect($errors->toArray());
        }

        return str_replace("'", '`', optional(collect($errors)->flatten())->implode(' '));
    }
}
