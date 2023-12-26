<?php

namespace Database\Factories;

use App\Enums\UserRoleEnum;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'avatar' => $this->faker->imageUrl(),
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'phone' => $this->faker->phoneNumber,
            'link' => null,
            'role' => $this->faker->randomElement(UserRoleEnum::getValues()),
            'bio' => $this->faker->boolean ? $this->faker->word : null,
            'position' => $this->faker->jobTitle,
            'gender' => $this->faker->boolean,
            'city' => $this->faker->city,
            'company_id' => $this->faker->randomElement(Company::query()->inRandomOrder()->get(id)),
        ];
    }
}
