<?php

namespace Database\Factories;

use Core\Domain\Enum\CastMemberType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CastMember>
 */
class CastMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->name(),
            'type' => CastMemberType::from(array_rand(CastMemberType::cases()) + 1),
        ];
    }
}
