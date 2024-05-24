@extends('layouts.main')

@section('content')
    <section class="md-section">
        <div class="container">
            <div class="layout layout-sidebar--right">
                <div class="row">
                    <div class="col-lg-8">
                        @yield('main')
                    </div>

                    <div class="col-lg-3 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1">
                        <div class="layout-sidebar">
                            @yield('sidebar')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
