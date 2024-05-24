<?php

namespace Core\Formatter;

use s9e\TextFormatter\Parser;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Unparser;
use s9e\TextFormatter\Configurator;
use Illuminate\Http\Request;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Core\Formatter\Event\Parsing;
use Core\Formatter\Event\Configuring;
use Core\Formatter\Event\Rendering;

class Formatter
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @param  Repository  $cache
     * @param  Dispatcher  $events
     * @param  string  $cacheDir
     */
    public function __construct(Repository $cache, Dispatcher $events, $cacheDir)
    {
        $this->cache    = $cache;
        $this->events   = $events;
        $this->cacheDir = $cacheDir;
    }

    /**
     * Parse text.
     *
     * @param  string  $text
     * @param  mixed  $context
     * @return string
     */
    public function parse($text, $context = null)
    {
        $parser = $this->getParser($context);

        $this->events->dispatch(new Parsing($parser, $context, $text));

        return $parser->parse($text);
    }

    /**
     * Render parsed XML.
     *
     * @param  string  $xml
     * @param  mixed  $context
     * @param  Request|null  $request
     * @return string
     */
    public function render($xml, $context = null, Request $request = null)
    {
        $renderer = $this->getRenderer();

        $this->events->dispatch(new Rendering($renderer, $context, $xml, $request));

        return $renderer->render($xml);
    }

    /**
     * Unparse XML.
     *
     * @param  string  $xml
     * @return string
     */
    public function unparse($xml)
    {
        return Unparser::unparse($xml);
    }

    /**
     * Flush the cache so that the formatter components are regenerated.
     */
    public function flush()
    {
        $this->cache->forget('core.formatter');
    }

    /**
     * @return Configurator
     */
    protected function getConfigurator()
    {
        $configurator = new Configurator;

        $configurator->rootRules->enableAutoLineBreaks();

        $configurator->rendering->engine           = 'PHP';
        $configurator->rendering->engine->cacheDir = $this->cacheDir;

        $configurator->enableJavaScript();
        $configurator->javascript->exports = ['preview'];

        $configurator->javascript->setMinifier('MatthiasMullieMinify')
            ->keepGoing = true;

        $configurator->Escaper;
        $configurator->Autoemail;
        $configurator->Autolink;
        $configurator->tags->onDuplicate('replace');

        $this->events->dispatch(new Configuring($configurator));

        $this->configureExternalLinks($configurator);

        return $configurator;
    }

    /**
     * @param  Configurator  $configurator
     */
    protected function configureExternalLinks(Configurator $configurator)
    {
        $dom = $configurator->tags['URL']->template->asDOM();

        foreach ($dom->getElementsByTagName('a') as $a) {
            $a->setAttribute('target', '_blank');
            $a->setAttribute('rel', 'nofollow');
        }

        $dom->saveChanges();
    }

    /**
     * Get a TextFormatter component.
     *
     * @param  string  $name  "renderer" or "parser" or "js"
     * @return mixed
     */
    protected function getComponent($name)
    {
        $formatter = $this->cache->rememberForever('core.formatter', function () {
            return $this->getConfigurator()->finalize();
        });

        return $formatter[$name];
    }

    /**
     * Get the parser.
     *
     * @param  mixed  $context
     * @return Parser
     */
    protected function getParser($context = null)
    {
        $parser = $this->getComponent('parser');

        $parser->registeredVars['context'] = $context;

        return $parser;
    }

    /**
     * Get the renderer.
     *
     * @return Renderer
     */
    protected function getRenderer()
    {
        spl_autoload_register(function ($class) {
            if (file_exists($file = $this->cacheDir.'/'.$class.'.php')) {
                include $file;
            }
        });

        return $this->getComponent('renderer');
    }

    /**
     * Get the formatter JavaScript.
     *
     * @return string
     */
    public function getJs()
    {
        return $this->getComponent('js');
    }
}
