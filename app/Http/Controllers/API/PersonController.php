<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of persons.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        return Person::with('phones')->paginate();
    }

    /**
     * Display the specified person.
     *
     * @param  Person  $person
     * @return Person
     */
    public function show(Person $person)
    {
        return $person->load('phones');
    }
}
