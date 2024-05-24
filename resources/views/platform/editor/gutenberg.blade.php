@extends('platform.layouts.editor')

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('vendor/gutenberg/gutenberg.css') }}">
    <link rel="stylesheet" href="{{ asset('css/editor.css') }}">

    <style>
        .app > .aside {
            margin-left: 0 !important;
            max-width: 160px !important;
            flex: 0 0 160px !important;
            overflow: hidden !important;
        }

        .app > .bg-white {
            flex: 1 0 0 !important;
            max-width: 100% !important;
        }

        .wrapper.mt-4 {
            display: none;
        }

        .edit-post-header {
            top: 0 !important;
        }

        .edit-post-sidebar,
        .edit-post-layout__content {
            top: 56px !important;
        }

        .edit-post-layout {
            padding-top: 0 !important;
        }

        .edit-post-header {
            left: 0;
        }

        .edit-post-layout__content {
            margin-left: 0;
        }

        body.gutenberg-editor-page .editor-post-title__block,
        body.gutenberg-editor-page .editor-default-block-appender,
        body.gutenberg-editor-page .editor-block-list__block {
            max-width: none !important;
        }

        .block-editor__container .wp-block {
            max-width: none !important;
        }
    </style>
@endpush

@section('body')
    <div id="editor" class="block-editor__container hide-if-no-js"></div>
@stop

@push('scripts')
    <script>
        window.gutenbergContent = @json(['raw' => $content ?? '']);
    </script>

    <script>
        window._gutenbergEditUrl = '{{ $edit_url ?? '' }}';
        window._gutenbergPreviewUrl = '{{ $preview_url ?? '' }}';

        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-start',
            showConfirmButton: false,
            timer: 3000,
        });

        window.onGutenbergSave = function(data) {
            axios.post('{!! $action ?? '/'.request()->path() !!}', {content: data}).then(function() {
                Toast.fire({
                    type: 'success',
                    title: 'Lưu nội dung thành công!',
                })
            });
        };
    </script>

    <script src="{{ mix('gutenberg.js', 'vendor/gutenberg') }}"></script>
    <script src="{{ asset('js/blocks.js') }}"></script>

    @if (request()->is('dashboard/menu/*'))
        <script src="{{ asset('js/blocks-menu.js') }}"></script>
    @endif
@endpush
