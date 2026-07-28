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
     * We sort any objects as they can
     * invalidate the storage.
     *
     * @return Collection
     */
    public function inputs()
    {
        return collect(request()->all())->filter(function ($item, $key) {
            if (is_object($item)) {
                return false;
            }

            return true;
        });
    }
}
