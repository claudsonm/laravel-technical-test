<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Person;
use Illuminate\Http\Request;
use App\Http\Resources\PersonResource;

class PersonController extends Controller
{
    /**
     * Display a listing of persons.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PersonResource::collection(Person::with('phones')->paginate());
    }

    /**
     * Display the specified person.
     *
     * @param  Person  $person
     * @return PersonResource
     */
    public function show(Person $person)
    {
        return PersonResource::make($person->load('phones'));
    }
}
