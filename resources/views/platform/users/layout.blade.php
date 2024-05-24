@extends('layouts.app')

@section('content')
    <section id="account-settings" class="container container-sm px-6 py-8 mb-4">
        <header class="mx-auto mb-6">
            <h1 class="font-normal mb-3">{{ __('Tài khoản') }}</h1>
        </header>

        <div class="row no-gutters bg-white position-relative rounded-lg shadow-sm">
            <aside class="col-md-4 col-lg-3 px-2 py-4 bg-light border-right">
                <div class="list-group list-group-flush">
                    <a href="{{ route('users.edit') }}" class="list-group-item {{ ($tab == 'profile') ? 'active' : '' }}">
                        <i class="fa fa-user" aria-hidden="true"></i> @lang('users.profile')
                    </a>

                    <a href="{{ route('users.password') }}" class="list-group-item {{ ($tab == 'security') ? 'active' : '' }}">
                        <i class="fa fa-lock" aria-hidden="true"></i> @lang('users.security')
                    </a>
                </div>
            </aside>

            <main class="col-md-8 col-lg-9" style="min-height: 500px;">
                @yield('main')
            </main>
        </div>
    </section>
@endsection
