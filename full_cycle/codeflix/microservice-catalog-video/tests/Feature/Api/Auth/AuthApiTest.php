<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Http\Response;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    public function testAuthenticationCategory()
    {
        $this->getJson('/api/categories')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->getJson('/api/categories/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->postJson('/api/categories')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->putJson('/api/categories/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->deleteJson('/api/categories/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthenticationGenre()
    {
        $this->getJson('/api/genres')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->getJson('/api/genres/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->postJson('/api/genres')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->putJson('/api/genres/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->deleteJson('/api/genres/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthenticationCastMember()
    {
        $this->getJson('/api/cast_members')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->getJson('/api/cast_members/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->postJson('/api/cast_members')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->putJson('/api/cast_members/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->deleteJson('/api/cast_members/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthenticationVideo()
    {
        $this->getJson('/api/videos')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->getJson('/api/videos/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->postJson('/api/videos')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->putJson('/api/videos/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->deleteJson('/api/videos/fake_id')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
