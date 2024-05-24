<?php

namespace Core\User;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;

class PermissionDeniedException extends AuthorizationException
{
    public function __construct($message = null, $code = 403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
