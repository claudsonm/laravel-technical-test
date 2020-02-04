<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Get a list of all users paginated.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        return Person::paginate();
    }
}
