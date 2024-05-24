@section('header')
    <div class="header">
        <div class="bg-cover flex" style="height: 255px;">
{{--            <img src="https://dashkit.goodthemes.co/assets/img/covers/profile-cover-1.jpg" class="header-img-top" alt="..." style="max-width: 100%;">--}}
        </div>

        <div class="container-fluid">
            <div class="header-body mt-n5 mt-md-n6">
                <div class="row align-items-end">
                    <div class="col-auto">
                        <div class="avatar avatar-xxl header-avatar-top avatar-placeholder avatar-orange">
                            <img src="{{ $user->avatar_url }}" alt="">
                        </div>
                    </div>

                    <div class="col mb-3 ml-n3 ml-md-n2">
                        <h6 class="header-pretitle mb-0">
                            Members
                        </h6>

                        <h1 class="header-title">{{ $user->name }}</h1>
                    </div>

                    <div class="col-12 col-md-auto mt-2 mt-md-0 mb-md-3">
                        <a href="#" class="btn btn-primary d-block d-md-inline-block">
                            Subscribe
                        </a>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col">
                        <ul class="nav nav-tabs nav-overflow header-tabs">
                            <li class="nav-item">
                                <a href="profile-posts.html" class="nav-link">
                                    Posts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="profile-groups.html" class="nav-link active">
                                    Groups
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="profile-projects.html" class="nav-link">
                                    Projects
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="profile-files.html" class="nav-link">
                                    Files
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="profile-subscribers.html" class="nav-link">
                                    Subscribers
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop

<div class="row">
    <div class="col col-md-3">
        <nav class="list-group">
            @foreach ($tabs as $link)
                {!! $link->addClass('list-group-item list-group-item-action')->render() !!}
            @endforeach
        </nav>
    </div>

    <div class="col">
        {!! $password_form !!}
    </div>
</div>
