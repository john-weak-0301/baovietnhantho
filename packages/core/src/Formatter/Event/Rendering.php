<?php

namespace Core\Formatter\Event;

use Illuminate\Http\Request;
use s9e\TextFormatter\Renderer;

class Rendering
{
    /**
     * @var Renderer
     */
    public $renderer;

    /**
     * @var mixed
     */
    public $context;

    /**
     * @var string
     */
    public $xml;

    /**
     * @var ServerRequestInterface
     */
    public $request;

    /**
     * @param  Renderer  $renderer
     * @param  mixed  $context
     * @param  string  $xml
     * @param  Request|null  $request
     */
    public function __construct(Renderer $renderer, $context, &$xml, Request $request = null)
    {
        $this->renderer = $renderer;
        $this->context  = $context;
        $this->xml      = &$xml;
        $this->request  = $request;
    }
}
