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

if (! function_exists('javascript_session_data')) {
    function javascript_session_data($nonce = false)
    {
        $user = optional(auth()->user())->jsonSessionData() ?? '{}';
        $message = session('message');
        $info = session('info');
        $warning = session('warning');
        $error = session_error_message();
        $nonce = $nonce ? ' nonce="'.$nonce.'"' : '';

        return <<<JS
            <script {$nonce}>
                window.app = {
                    session: {
                        user: {$user},
                        message: '{$message}',
                        info: '{$info}',
                        warning: '{$warning}',
                        error: '{$error}'
                    }
                }
            </script>
    JS;
    }
}
