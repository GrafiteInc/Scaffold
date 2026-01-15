<?php

namespace Tests\Feature\Resources;

use App\Http\Resources\UserResource;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    public function test_user_resource()
    {
        $request = new \Illuminate\Http\Request;

        $users = collect([$this->user]);

        $resource = new UserResource($users);

        $this->assertEquals($this->user->name, $resource->first()->name);
    }
}
