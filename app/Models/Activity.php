<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    /**
     * Request keys whose values are never stored or shown.
     * Any key containing "password" is also treated as sensitive.
     */
    public const SENSITIVE_KEYS = [
        'password',
        'password_confirmation',
        'old_password',
        'new_password',
        'new_password_confirmation',
        'current_password',
        'token',
        'secret',
        'two_factor_code',
        'one_time_password',
    ];

    /**
     * Framework keys that carry no meaning for a reader.
     */
    public const HIDDEN_KEYS = [
        '_token',
        '_method',
    ];

    public const MASK = '••••••';

    public $fillable = [
        'user_id',
        'description',
        'request',
    ];

    public static $rules = [
        'request' => 'required',
    ];

    public $casts = [
        'request' => 'json',
    ];

    public static function isSensitiveKey($key): bool
    {
        if (! is_string($key)) {
            return false;
        }

        $key = Str::lower($key);

        return in_array($key, self::SENSITIVE_KEYS, true) || Str::contains($key, 'password');
    }

    /**
     * Drop framework keys and mask sensitive values, at any depth.
     */
    public static function redact(array $data): array
    {
        $clean = [];

        foreach ($data as $key => $value) {
            if (is_string($key) && in_array(Str::lower($key), self::HIDDEN_KEYS, true)) {
                continue;
            }

            if (self::isSensitiveKey($key)) {
                $clean[$key] = self::MASK;

                continue;
            }

            $clean[$key] = is_array($value) ? self::redact($value) : $value;
        }

        return $clean;
    }

    public function method(): string
    {
        return Str::upper($this->request['method'] ?? 'GET');
    }

    public function url(): string
    {
        return (string) ($this->request['query'] ?? $this->request['url'] ?? '');
    }

    public function path(): string
    {
        $url = $this->url();
        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $query = parse_url($url, PHP_URL_QUERY);

        return $query ? "{$path}?{$query}" : $path;
    }

    public function ip(): ?string
    {
        return $this->request['client_ip'] ?? null;
    }

    public function isSecure(): bool
    {
        return (bool) ($this->request['secure'] ?? false);
    }

    public function methodBadge(): string
    {
        return match ($this->method()) {
            'POST' => 'bg-primary',
            'PUT', 'PATCH' => 'bg-warning',
            'DELETE' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function payload(): array
    {
        $payload = $this->request['payload'] ?? [];

        return is_array($payload) ? self::redact($payload) : [];
    }
}
