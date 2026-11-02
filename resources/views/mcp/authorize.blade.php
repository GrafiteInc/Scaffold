@extends('layouts.guest')

@section('page-title', 'Authorize '.$client->name)

@section('content')
    <div class="content-sm mt-4">

        <div class="text-center mb-4">
            @if ($client->logo_uri ?? null)
                <img src="{{ $client->logo_uri }}" alt="{{ $client->name }}" class="rounded mb-3" style="height: 48px; width: 48px; object-fit: contain;">
            @else
                <span class="fas fa-fw fa-shield-halved fa-3x text-primary mb-3"></span>
            @endif

            <h3 class="mb-2">Authorize {{ $client->name }}</h3>

            <p class="text-body-secondary m-0">This application would like to connect to your {{ config('app.name') }} account.</p>

            @if ($client->client_uri ?? null)
                <a href="{{ $client->client_uri }}" target="_blank" rel="noopener noreferrer" class="small">{{ $client->client_uri }}</a>
            @endif
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="bg-body-tertiary border-start border-primary bmx-border-3 p-3 mb-4">
                    <p class="small text-body-secondary mb-1">Logged in as</p>
                    <p class="fw-medium m-0">{{ $user->email }}</p>
                </div>

                @if (count($scopes) > 0)
                    <p class="fw-medium mb-2">This will allow it to:</p>
                    <ul class="list-unstyled mb-4">
                        @foreach ($scopes as $scope)
                            <li class="d-flex align-items-start gap-2 mb-1">
                                <span class="fas fa-fw fa-check text-primary mt-1"></span>
                                <span>{{ $scope->description }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="d-flex gap-3">
                    <form method="POST" action="{{ route('passport.authorizations.deny') }}" class="flex-fill" id="denyForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="state" value="{{ $request->state }}">
                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                        <input type="hidden" name="auth_token" value="{{ $authToken }}">
                        <button type="submit" class="btn btn-outline-secondary w-100">
                            <span class="fas fa-fw fa-times"></span> Cancel
                        </button>
                    </form>

                    <form method="POST" action="{{ route('passport.authorizations.approve') }}" class="flex-fill" id="authorizeForm">
                        @csrf
                        <input type="hidden" name="state" value="{{ $request->state }}">
                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                        <input type="hidden" name="auth_token" value="{{ $authToken }}">
                        <button type="submit" class="btn btn-primary w-100" id="authorizeButton">
                            <span class="fas fa-fw fa-check"></span> Authorize
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- MCP clients open this page in a popup; close it once the redirect back to the client has fired. --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const authorizeForm = document.getElementById('authorizeForm');
            const button = document.getElementById('authorizeButton');

            authorizeForm.addEventListener('submit', function () {
                button.disabled = true;
                button.textContent = 'Authorizing...';

                setTimeout(function () {
                    const checkRedirect = setInterval(function () {
                        if (! window.location.href.includes('/oauth/authorize')
                            || window.location.search.includes('code=')
                            || window.location.search.includes('error=')) {
                            clearInterval(checkRedirect);
                            window.close();
                        }
                    }, 100);

                    setTimeout(function () {
                        clearInterval(checkRedirect);
                        window.close();
                    }, 5000);
                }, 200);
            });

            document.getElementById('denyForm').addEventListener('submit', function () {
                setTimeout(function () {
                    window.close();
                }, 200);
            });
        });
    </script>
@stop
