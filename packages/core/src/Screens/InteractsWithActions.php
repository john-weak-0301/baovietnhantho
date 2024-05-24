<?php

namespace Core\Screens;

use Throwable;
use Core\Actions\ActionRequest;

trait InteractsWithActions
{
    /**
     * Perform an action on the specified resources.
     *
     * @param  ActionRequest  $request
     * @return mixed|void
     *
     * @throws Throwable
     */
    public function handleAction(ActionRequest $request)
    {
        $request->validateFields();

        $response = $request->action()->handleRequest($request);

        return redirect(action([static::class, 'handle']));
    }
}
