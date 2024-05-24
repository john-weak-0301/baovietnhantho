<?php

namespace Core\Formatter\Event;

use s9e\TextFormatter\Configurator;

class Configuring
{
    /**
     * @var Configurator
     */
    public $configurator;

    /**
     * @param Configurator $configurator
     */
    public function __construct(Configurator $configurator)
    {
        $this->configurator = $configurator;
    }
}
