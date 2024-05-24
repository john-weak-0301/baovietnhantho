<?php

namespace Core\Formatter\Event;

use s9e\TextFormatter\Parser;

class Parsing
{
    /**
     * @var Parser
     */
    public $parser;

    /**
     * @var mixed
     */
    public $context;

    /**
     * @var string
     */
    public $text;

    /**
     * @param  Parser  $parser
     * @param  mixed  $context
     * @param  string  $text
     */
    public function __construct(Parser $parser, $context, &$text)
    {
        $this->parser  = $parser;
        $this->context = $context;
        $this->text    = &$text;
    }
}
