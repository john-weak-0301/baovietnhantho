<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Platform\Models\Role;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Dashboard;

class RoleEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Roles';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Access rights';

    /**
     * Query data.
     *
     * @param  Role  $role
     *
     * @return array
     */
    public function query(Role $role): array
    {
        $rolePermission = $role->permissions ?? [];
        $permission     = Dashboard::getPermission()
                                   ->sort()
                                   ->transform(function ($group) use ($rolePermission) {
                                       $group = collect($group)->sortBy('description')->toArray();

                                       foreach ($group as $key => $value) {
                                           $group[$key]['active'] = array_key_exists($value['slug'], $rolePermission);
                                       }

                                       return $group;
                                   });

        return [
            'permission' => $permission,
            'role'       => $role,
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
            Link::name(__('Save'))
                ->icon('icon-check')
                ->method('save'),

            Link::name(__('Remove'))
                ->icon('icon-trash')
                ->method('remove'),
        ];
    }

    /**
     * Views.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            Layouts\RoleEditLayout::class,
            Layouts\RolePermissionLayout::class,
        ];
    }

    /**
     * @param  Role  $role
     * @param  Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Role $role, Request $request)
    {
        $role->fill($request->get('role'));

        foreach ($request->get('permissions', []) as $key => $value) {
            $permissions[base64_decode($key)] = 1;
        }

        $role->permissions = $permissions ?? [];
        $role->save();

        Alert::info(__('Role was saved'));

        return redirect()->route('platform.systems.roles');
    }

    /**
     * @param  Role  $role
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function remove(Role $role)
    {
        $role->delete();

        Alert::info(__('Role was removed'));

        return redirect()->route('platform.systems.roles');
    }
}
