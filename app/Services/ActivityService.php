<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Support\Collection;

class ActivityService
{
    public $model;

    public function __construct(Activity $model)
    {
        $this->model = $model;
    }

    /**
     * Create an activity record.
     *
     * @param  string  $description
     * @return Activity
     */
    public function log($description = '')
    {
        $payload = [
            'user_id' => auth()->id(),
            'description' => $description,
            'request' => [
                'url' => request()->url(),
                'method' => request()->method(),
                'query' => request()->fullUrl(),
                'secure' => request()->secure(),
                'client_ip' => request()->ip(),
                'payload' => $this->inputs(),
            ],
        ];

        return $this->model->create($payload);
    }

    /**
     * Objects (uploads) are dropped as they can invalidate the
     * storage, and secrets such as passwords are masked.
     *
     * @return Collection
     */
    public function inputs()
    {
        $inputs = collect(request()->all())
            ->reject(fn ($item) => is_object($item))
            ->all();

        return collect(Activity::redact($inputs));
    }
}
