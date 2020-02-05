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
    public function more_persons_can_be_retrieved_using_query_parameters()
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

    /** @test */
    public function only_authorized_requests_are_allowed_to_get_a_person_data()
    {
        create(Person::class);
        $this->getJson('api/persons/1')
            ->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    public function it_can_display_the_data_of_a_single_person()
    {
        $this->signInAsClient();
        $person = create(Person::class);

        $this->getJson('api/persons/1')
            ->assertSuccessful()
            ->assertJsonFragment(['name' => $person->name]);
    }

    /** @test */
    public function an_error_is_thrown_when_trying_to_get_data_of_non_existing_person()
    {
        $this->signInAsClient();

        $this->getJson('api/persons/41')
            ->assertNotFound()
            ->assertExactJson(['message' => 'No results for the given resource.']);
    }
}
