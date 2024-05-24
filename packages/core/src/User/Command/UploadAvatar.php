<?php

namespace Core\User\Command;

use Core\User\User;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadAvatar
{
    use SerializesModels;

    /**
     * The ID of the user to upload the avatar for.
     *
     * @var User
     */
    public $user;

    /**
     * The avatar file to upload.
     *
     * @var UploadedFileInterface
     */
    public $file;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param  User  $user
     * @param  UploadedFile  $file
     * @param  User  $actor
     */
    public function __construct(User $user, UploadedFile $file, User $actor)
    {
        $this->user  = $user;
        $this->file  = $file;
        $this->actor = $actor;
    }
}
