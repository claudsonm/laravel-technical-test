<?php

namespace App\Handlers;

use SimpleXMLElement;

abstract class Handler
{
    protected SimpleXMLElement $content;

    public function __construct(SimpleXMLElement $content)
    {
        $this->content = $content;
    }

    /**
     * Handles the XML content processing persisting into the database.
     *
     * @return $this
     */
    abstract public function handle(): self;

    /**
     * Returns an array where the first index is the output message and the
     * second index is the severity level for the message.
     *
     * @return array
     */
    abstract public function getOutput() : array;
}
