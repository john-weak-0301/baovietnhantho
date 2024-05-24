<?php

namespace Core\User\OAuth;

use Exception;
use Core\User\User;
use Core\User\LoginProvider;
use Core\User\UserRepository;
use Core\User\Event\RegisteringFromProvider;
use Illuminate\Support\Facades\Auth;

class ResponseFactory
{
    /**
     * @var bool
     */
    protected $allowedCreate;

    /**
     * Constructor.
     *
     * @param  bool  $allowedCreate
     */
    public function __construct(bool $allowedCreate = false)
    {
        $this->allowedCreate = $allowedCreate;
    }

    /**
     * //
     *
     * @param  string  $provider
     * @param  string  $identifier
     * @param  callable  $configureRegistration
     * @return array
     */
    public function make(string $provider, string $identifier, callable $configureRegistration)
    {
        if ($user = LoginProvider::logIn($provider, $identifier)) {
            return $this->makeLoggedInResponse($user);
        }

        // Configure the registration.
        $configureRegistration($registration = new Registration);

        $provided = $registration->getProvided();

        // If provided an email and that email is existing
        // on our system, just let the user go on.
        if (!empty($provided['email']) && $user = app(UserRepository::class)->findByEmail($provided['email'])) {
            $user->loginProviders()->create(compact('provider', 'identifier'));

            return $this->makeLoggedInResponse($user);
        }

        // Create new user without password.
        if ($this->allowedCreate && !empty($provided['email'])) {
            $user = $this->registerUserWithoutPassword($provided, $registration->getSuggested());

            event(new RegisteringFromProvider($user, $provider, [
                'identifier'   => $identifier,
                'registration' => $registration,
            ]));

            $user->loginProviders()->create(compact('provider', 'identifier'));

            if (isset($provided['avatar_url'])) {
                try {
                    $user->changeAvatar($provided['avatar_url'])->save();
                } catch (Exception $e) {
                    report($e);
                }
            }

            return $this->makeLoggedInResponse($user);
        }

        // Come to first time, make a response redirect to
        // the register page with suggested infomations.
        return $this->makeResponse(array_merge(
            $provided,
            $registration->getSuggested(),
            [
                'provided' => array_keys($provided),
            ]
        ));
    }

    /**
     * //
     *
     * @param  User  $user
     * @return mixed
     */
    protected function makeLoggedInResponse(User $user)
    {
        $this->guard()->login($user, true);

        return $this->makeResponse(['logged_in' => true]);
    }

    /**
     * //
     *
     * @param  array  $payload
     * @return array
     */
    protected function makeResponse(array $payload)
    {
        return $payload;
    }

    /**
     * //
     *
     * @param  array  $provided
     * @param  array  $suggested
     * @return User
     */
    protected function registerUserWithoutPassword(array $provided, array $suggested): User
    {
        return UserRepository::newModel()->create([
            'email'    => clean($provided['email']),
            'name'     => clean($suggested['name'] ?? ''),
            'password' => '',
        ]);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
