<?php

namespace Tests\Feature\Helpers;

use Tests\TestCase;
use App\Http\Resources\UserResource;

class UserResourceTest extends TestCase
{
    public function testUserResource()
    {
        $request = new \Illuminate\Http\Request();

        $users = collect([$this->user]);

        $resource = new UserResource($users);

        $this->assertEquals($this->user->name, $resource->first()->name);
    }
}
