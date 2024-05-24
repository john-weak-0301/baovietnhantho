<div class="row row-eq-height">
    @foreach($posts as $post)
        <div class="col-sm-6 col-md-6 col-lg-3 ">

            <div class="new-02">
                <div class="new-02__media" style="background-image: url({{ $post->image_url }});">
                    <img src="{{ $post->image_url }}" alt="{{ esc_attr($post->title) }}" />
                </div>

                <div class="new-02__body">
                    <h3 class="new-02__title">
                        <a href="{{ $post->getUrl() }}" title="{{ $post->title }}">{{ Str::words($post->title, 12) }}</a>
                    </h3>

                    <p class="new-02__text">{{ Str::words($post->excerpt, 20) }}</p>
                </div>
            </div>

        </div>
    @endforeach
</div>
