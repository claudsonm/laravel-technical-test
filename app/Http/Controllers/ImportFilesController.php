<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImportFilesRequest;

class ImportFilesController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('files.import');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImportFilesRequest $request)
    {
        $file = $request->file('document');
        $array = $this->getXmlAsArray($file);

        return $array;
    }


    /**
     * @param $file
     * @return array
     */
    protected function getXmlAsArray($file) : array
    {
        $xml = simplexml_load_string(file_get_contents($file), "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(406, 'We could not parse the given file.');
        }

        return $array;
    }
}
