<?php

namespace Tests\Feature;

use App\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_authorized_requests_are_allowed_to_list_the_persons()
    {
        $this->getJson('api/persons')
            ->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    public function a_list_of_persons_can_be_retrieved()
    {
        $this->signInAsClient();
        $persons = create(Person::class, [],20);

        $this->getJson('api/persons')
            ->assertSuccessful()
            ->assertJsonStructure(['current_page', 'data', 'total'])
            ->assertJsonFragment(['name' => $persons->first()->name])
            ->assertDontSee($persons->last()->name);
    }

    /** @test */
    public function other_pages_can_be_accessed_using_query_parameters()
    {
        $this->signInAsClient();
        $persons = create(Person::class, [],20);

        $this->getJson('api/persons?page=2')
            ->assertSuccessful()
            ->assertJsonStructure(['current_page', 'data', 'total'])
            ->assertJsonFragment(['current_page' => 2])
            ->assertJsonFragment(['name' => $persons->last()->name])
            ->assertDontSee($persons->first()->name);
    }
}
