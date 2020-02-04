<?php

namespace App\Handlers;

abstract class Handler
{
    protected array $content;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    /**
     * Handle the XML content persisting into the database.
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
