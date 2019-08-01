<?php

namespace App\Services;

use App\Models\Activity;

class ActivityService
{
    public function __construct(Activity $model)
    {
        $this->model = $model;
    }

    /**
     * Create an activity record
     *
     * @param  string $description
     * @return  \App\Models\Activity
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
                'payload' => request()->all(),
            ],
        ];

        return $this->model->create($payload);
    }
}
