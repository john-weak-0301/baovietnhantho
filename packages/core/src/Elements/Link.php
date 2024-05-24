<?php

namespace Core\Elements;

use Spatie\Menu\Link as MenuLink;

class Link extends MenuLink
{
    /**
     * Constructor.
     *
     * @param  string|null  $url
     * @param  string|null  $text
     */
    public function __construct(string $url = null, string $text = null)
    {
        parent::__construct($url ?? '', $text ?? '');
    }

    /**
     * //
     *
     * @return string
     */
    public function build()
    {
        return $this->render();
    }

    /**
     * Sets the link url.
     *
     * @param  string|null  $url
     * @return $this
     */
    public function link(string $url)
    {
        return $this->setUrl($url);
    }

    /**
     * Sets link url to a route.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @param  bool  $absolute
     * @return $this
     */
    public function route(string $name, $parameters = [], $absolute = true)
    {
        return $this->link(route($name, $parameters, $absolute));
    }

    /**
     * Sets link url to a route.
     *
     * @param  string|array  $action
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return $this
     */
    public function action($action, $parameters = [], bool $absolute = true)
    {
        if (is_array($action)) {
            $action = implode('@', $action);
        }

        return $this->link(action($action, $parameters, $absolute));
    }

    /**
     * Add [target="_blank"] attribute.
     *
     * @return Link
     */
    public function openNewTab()
    {
        return $this->setAttribute('target', '_blank');
    }

    /**
     * Add [rel="nofollow"] attribute.
     *
     * @return $this
     */
    public function noFollow()
    {
        return $this->setAttribute('rel', 'nofollow');
    }

    /**
     * Consider this is external link.
     *
     * @return $this
     */
    public function external()
    {
        $this->openNewTab();
        $this->noFollow();

        return $this;
    }

    /**
     * //
     *
     * @param  string  $iconClass
     * @param  bool  $append
     * @return $this
     */
    public function icon(string $iconClass, bool $append = false)
    {
        $iconTag = sprintf('<i class="%s m-r-sm" aria-hidden="true"></i>', $iconClass);

        if ($append) {
            $this->text .= $iconTag;
        } else {
            $this->text = $iconTag.$this->text;
        }

        return $this;
    }
}
