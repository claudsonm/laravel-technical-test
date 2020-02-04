<?php

namespace App\Http\Controllers;

use App\Exceptions\ParserException;
use App\Http\Requests\ImportFilesRequest;
use App\Parsers\Parser;
use Exception;
use Illuminate\Support\Str;

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
     * @throws ParserException
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ImportFilesRequest $request)
    {
        $file = $request->file('document');
        try {
            $content = $this->getXmlAsArray($file);
            $baseName = Str::studly(array_key_first($content)).'XmlHandler';
            if (class_exists($handlerClass = "App\\Handlers\\${baseName}")) {
                [$message, $level] = (new $handlerClass($content))->handle()->getOutput();
                flash($message)->{$level}();

                return redirect()->back();
            }

            throw new ParserException('There are no handlers for the given file.');
        } catch (Exception $exception) {
            flash($exception->getMessage())->error();
        }

        return redirect()->back();
    }

    /**
     * @param $file
     *
     * @throws ParserException
     * @return array
     */
    protected function getXmlAsArray($file): array
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string(file_get_contents($file));
        if (! $xml) {
            $firstError = libxml_get_errors()[0]->message;

            throw new ParserException("The XML is invalid: {$firstError}");
        }
        $array = json_decode(json_encode($xml), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new ParserException('We could not parse the given file.');
        }

        return $array;
    }
}
