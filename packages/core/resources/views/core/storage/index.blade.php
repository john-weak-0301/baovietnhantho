@extends('platform::dashboard')

@section('title', __('Quản lý Files'))
@section('description', __('Quản lý Files'))

@section('content')
    <div id="filemanager">
        <media-manager
            base-path="{{ config('voyager.media.path', '/') }}"
            :routes="{{ json_encode([
                            'files' => route('platform.systems.media.files'),
                            'mkdir' => route('platform.systems.media.mkdir'),
                            'delete' => route('platform.systems.media.delete'),
                            'rename' => route('platform.systems.media.rename'),
                            'upload' => route('platform.systems.media.upload'),
                            'remove' => route('platform.systems.media.remove'),
                            'move' => route('platform.systems.media.move'),
                            'crop' => route('platform.systems.media.crop'),
                        ]) }}"
            :show-folders="{{ config('voyager.media.show_folders', true) ? 'true' : 'false' }}"
            :allow-upload="{{ config('voyager.media.allow_upload', true) ? 'true' : 'false' }}"
            :allow-move="{{ config('voyager.media.allow_move', true) ? 'true' : 'false' }}"
            :allow-delete="{{ config('voyager.media.allow_delete', true) ? 'true' : 'false' }}"
            :allow-create-folder="{{ config('voyager.media.allow_create_folder', true) ? 'true' : 'false' }}"
            :allow-rename="{{ config('voyager.media.allow_rename', true) ? 'true' : 'false' }}"
            :allow-crop="{{ config('voyager.media.allow_crop', true) ? 'true' : 'false' }}"
            :details="{{ json_encode(['thumbnails' => config('voyager.media.thumbnails', []), 'watermark' => config('voyager.media.watermark', (object)[])]) }}"
        ></media-manager>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('js/media-manager.js') }}"></script>
@endpush
