<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use Orchid\Screen\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'User';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'All registered users';

    /**
     * Query data.
     *
     * @param  \Orchid\Platform\Models\User  $user
     *
     * @return array
     */
    public function query(User $user): array
    {
        $user->load(['roles']);

        return [
            'user'       => $user,
            'permission' => $user->getStatusPermission(),
        ];
    }

    /**
     * Button commands.
     *
     * @return array
     */
    public function commandBar(): array
    {
        return [

            Link::name(__('Cài đặt'))
                ->icon('icon-open')
                ->group([
                    Link::name(__('Đăng nhập với người dùng'))
                        ->icon('icon-login')
                        ->method('switchUserStart'),

                    Link::name(__('Đổi mật khẩu'))
                        ->icon('icon-lock-open')
                        ->title(__('Đổi mật khẩu'))
                        ->method('changePassword')
                        ->modal('password'),
                ]),

            Link::name(__('Lưu'))
                ->icon('icon-check')
                ->method('save'),

            Link::name(__('Xóa'))
                ->icon('icon-trash')
                ->method('remove'),
        ];
    }

    /**
     * @return array
     * @throws \Throwable
     *
     */
    public function layout(): array
    {
        return [
            Layouts\UserEditLayout::class,

            Layouts\UserRoleLayout::class,

            Layout::modal('password', [
                Layout::rows([
                    Password::make('password')
                        ->title(__('Mật khẩu'))
                        ->required()
                        ->placeholder(__('Nhập mật khẩu')),
                ]),
            ]),
        ];
    }

    /**
     * @param  \Orchid\Platform\Models\User  $user
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(User $user, Request $request)
    {
        $permissions = $request->get('permissions', []);
        $roles       = $request->input('user.roles', []);

        foreach ($permissions as $key => $value) {
            unset($permissions[$key]);
            $permissions[base64_decode($key)] = $value;
        }

        $user
            ->fill($request->get('user'))
            ->replaceRoles($roles)
            ->fill([
                'permissions' => $permissions,
            ])
            ->save();

        Alert::info(__('Đã lưu thông tin người dùng'));

        return redirect()->route('platform.systems.users');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id)
    {
        $user = User::findOrNew($id);

        $user->delete();

        Alert::info(__('Người dùng đã xóa'));

        return redirect()->route('platform.systems.users');
    }

    /**
     * @param  \Orchid\Platform\Models\User  $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchUserStart(User $user, Request $request)
    {
        if (!session()->has('original_user')) {
            session()->put('original_user', $request->user()->id);
        }
        Auth::login($user);

        return back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchUserStop()
    {
        $id = session()->pull('original_user');
        Auth::loginUsingId($id);

        return back();
    }

    /**
     * @param  User  $user
     * @param  Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(User $user, Request $request)
    {
        $user->password = Hash::make($request->get('password'));
        $user->save();

        Alert::info(__('Đã lưu thông tin người dùng'));

        return back();
    }
}
