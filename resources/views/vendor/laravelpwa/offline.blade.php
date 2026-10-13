{{--
    The service worker's offline fallback. Deliberately standalone: inlined CSS,
    no layout, no Vite, no CDNs and no session. The worker precaches this
    response on install and serves it whenever a page load fails, so it has to
    render for anyone with no network at all.
--}}
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="{{ config('laravelpwa.manifest.theme_color', '#000000') }}">
        <title>{{ config('app.name') }} | Offline</title>
        <style>
            :root {
                --background: #f8f9fa;
                --surface: #ffffff;
                --text: #212529;
                --muted: #6c757d;
                --border: #dee2e6;
                --accent: #0d6efd;
            }

            @media (prefers-color-scheme: dark) {
                :root {
                    --background: #111111;
                    --surface: #1c1c1e;
                    --text: #f1f3f5;
                    --muted: #adb5bd;
                    --border: #343a40;
                    --accent: #6ea8fe;
                }
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 16px;
                background: var(--background);
                color: var(--text);
                font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }

            main {
                width: 100%;
                max-width: 420px;
                padding: 32px 24px;
                text-align: center;
                background: var(--surface);
                border: 1px solid var(--border);
                border-radius: 12px;
            }

            h1 {
                margin: 0 0 8px;
                font-size: 1.25rem;
            }

            p {
                margin: 0 0 24px;
                color: var(--muted);
                line-height: 1.5;
            }

            a {
                display: inline-block;
                padding: 8px 20px;
                color: var(--accent);
                border: 1px solid var(--accent);
                border-radius: 6px;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <main>
            <h1>You're offline</h1>
            <p>{{ config('app.name') }} can't reach the network right now. Check your connection and try again.</p>
            <a href="/">Try again</a>
        </main>
    </body>
</html>
