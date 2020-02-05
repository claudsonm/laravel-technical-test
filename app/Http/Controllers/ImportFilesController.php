<?php

namespace App\Http\Controllers;

use App\Exceptions\ParserException;
use App\Http\Requests\ImportFilesRequest;
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ImportFilesRequest $request)
    {
        $file = $request->file('document');

        try {
            $content = $this->parseXml($file);
            $handler = $this->getHandlerFor($content);
            if (class_exists($handler)) {
                [$message, $level] = (new $handler($content))->handle()->getOutput();
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
     * Parse the given XML file into a instance of SimpleXMLElement.
     *
     * @param $file
     *
     * @throws ParserException
     *
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
     * Get the handler namespace for the given XML based on the root element.
     */
    protected function getHandlerFor(SimpleXMLElement $xml): string
    {
        $class = Str::studly($xml->getName()).'XmlHandler';

        return "App\\Handlers\\{$class}";
    }
}
