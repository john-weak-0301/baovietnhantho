@extends('layouts.main')

@section('content')
    <section class="md-section bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">

                    <div class="title md-text-center">
                        <h2 class="title__title">{{ __('Kết quả tìm kiếm') }}</h2>
                    </div>

                    <form class="form-search form-search__light" method="GET" action="/tim-kiem">
                        <input class="form-control" type="text" name="q" value="{{ rawurldecode(request('q')) }}" placeholder="Nhập thông tin cần tìm kiếm" />
                        <span class="form-icon"><img src="/img/svg/icon-search.svg" alt="Tìm kiếm" /></span>
                    </form><!-- End / form-search -->

                    <div class="nav-wrap-scroll search-page-nav">
                        <ul>
                            <li class="{{ !request('type') ? 'current' : ''  }}">
                                <a href="{{ route('search') }}?q={{ request('q') }}">{{ __('Tất cả') }}</a>
                            </li>

                            <li class="{{ request('type') && request('type') === 'products' ? 'current' : ''  }}">
                                <a href="{{ route('search') }}?q={{ request('q') }}&type=products">{{ __('Sản phẩm') }}</a>
                            </li>

                            <li class="{{ request('type') && request('type') === 'services' ? 'current' : ''  }}">
                                <a href="{{ route('search') }}?q={{ request('q') }}&type=services">{{ __('Dịch vụ khách hàng') }}</a>
                            </li>

                            <li class="{{ request('type') && request('type') === 'news' ? 'current' : ''  }}">
                                <a href="{{ route('search') }}?q={{ request('q') }}&type=news">{{ __('Tin tức') }}</a>
                            </li>

                            <li class="{{ request('type') && request('type') === 'experience' ? 'current' : ''  }}">
                                <a href="{{ route('search') }}?q={{ request('q') }}&type=experience">{{ __('Kinh nghiệm') }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="search-page__list">
                        <ul>
                            @isset($results)
                                @foreach($results->groupByType() as $type => $modelSearchResults)
                                    @foreach($modelSearchResults as $searchResult)
                                        <li>
                                            <h4 class="search-page__listitle"><a
                                                    href="{{ $searchResult->url }}">{{ $searchResult->title }}</a>
                                            </h4>

                                            <p>{{ $searchResult->searchable->excerpt }}</p>
                                        </li>
                                    @endforeach
                                @endforeach
                            @else
                                <p class="text-center">Vui lòng nhập từ khóa tìm kiếm</p>
                            @endisset
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
