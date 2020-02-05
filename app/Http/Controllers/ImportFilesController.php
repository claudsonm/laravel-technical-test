<?php

namespace App\Http\Controllers;

use App\Exceptions\ParserException;
use App\Http\Requests\ImportFilesRequest;
use App\Parsers\Parser;
use Exception;
use Illuminate\Support\Str;
use SimpleXMLElement;

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
            $content = $this->parseXml($file);
            if (class_exists($class = $this->getHandlerFor($content))) {
                [$message, $level] = (new $class($content))->handle()->getOutput();
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
     * @throws ParserException
     * @return SimpleXMLElement
     */
    protected function parseXml($file)
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string(file_get_contents($file));
        if (! $xml) {
            $firstError = libxml_get_errors()[0]->message;

            throw new ParserException("The XML is invalid: {$firstError}");
        }

        return $xml;
    }

    /**
     * @param  SimpleXMLElement  $xml
     * @return string
     */
    protected function getHandlerFor(SimpleXMLElement $xml): string
    {
        $class = Str::studly($xml->getName()).'XmlHandler';

        return "App\\Handlers\\${class}";
    }
}
