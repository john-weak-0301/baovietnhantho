<div class="row">
    <div class="col-md-12">
        <div class="card mb-2">
            <div class="card-body text-center">
                <h2 v-pre class="card-title mb-0">{{ $user->name }}</h2>
                <small class="card-subtitle mb-2 text-muted">{{ $user->email }}</small>

                <div class="card-text row mt-3">
                    <div class="col-md-4">
                        <span class="text-muted d-block">@lang('comments.comments')</span>
                        {{ $comments_count ?? 0 }}
                    </div>

                    <div class="col-md-4">
                        <span class="text-muted d-block">@lang('posts.posts')</span>
                        {{ $posts_count ?? 0 }}
                    </div>

                    <div class="col-md-4">
                        <span class="text-muted d-block">@lang('likes.likes')</span>
                        {{ $likes_count ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
