<?php

namespace Core\Elements\Table;

use Core\Actions\Action;
use Illuminate\Support\Collection;

trait InteractsWithActions
{
    /**
     * Store the actions.
     *
     * @var Collection
     */
    protected $actions;

    /**
     * Action handle URL.
     *
     * @var string
     */
    protected $actionHandleUrl;

    /**
     * Get the actions.
     *
     * @return Collection|Action[]
     */
    public function getActions()
    {
        if ($this->screen && !blank($actions = $this->screen->availableActions())) {
            return $actions;
        }

        if (!$this->actions) {
            return collect();
        }

        return $this->actions->whereInstanceOf(Action::class);
    }

    /**
     * Sets the actions.
     *
     * @param  Collection  $actions
     * @return $this
     */
    public function withActions(Collection $actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Gets the action handle url.
     *
     * @return string
     */
    public function getActionHandleUrl()
    {
        return $this->actionHandleUrl ?? url()->current().'/action';
    }

    /**
     * Sets the action handle Url.
     *
     * @param  string  $url
     * @return $this
     */
    public function setActionHandleUrl(string $url)
    {
        $this->actionHandleUrl = $url;

        return $this;
    }
}
