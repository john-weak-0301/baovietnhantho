<div data-controller="upload-screen">
    <div id="dropzone" class="dropzone-area dropzone-multiple" data-options='{"url": "https://"}'>
        <div class="dropzone-fallback fallback">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFileUploadMultiple" multiple>
                <label class="custom-file-label" for="customFileUploadMultiple">Choose file</label>
            </div>
        </div>

        <div class="dz-message mb-2">
            <p class="m-t-md text-primary">{{ get_feather_icon('upload-cloud', 48) }}</p>
            <p class="font-bold text-gray-dark">{{ __('Drag files here or click to upload.')}}</p>
            <small class="w-b-k">{{ __('(Files are processed automatically, you just need to specify their order)') }}</small>
        </div>

        <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush"></ul>
    </div>
</div>

@push('templates')
    <script type="text/html" id="html-dropzone-preview">
        <li class="list-group-item px-0">
            <div class="row row-sm align-items-center">
                <div class="col-auto">
                    <div class="avatar avatar-lg">
                        <img class="avatar-img rounded-circle" src="" alt="" data-dz-thumbnail>
                        <span class="avatar-status bg-warning"></span>
                        <span class="avatar-status bg-success"></span>
                    </div>
                </div>

                <div class="col ml-n2">
                    <h4 class="mb-1 text-truncate" data-dz-name></h4>
                    <p class="mb-0">
                        <span class="small text-muted" data-dz-size></span>
                        <span class="small text-danger" data-dz-errormessage></span>
                    </p>
                </div>

                <div class="col-auto">
                    <div class="progress progress-xs" style="width: 10rem;">
                        <div class="progress-bar" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 0;" data-dz-uploadprogress></div>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="dropdown">
                        <button class="dropdown-ellipses dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ get_feather_icon('more-vertical') }}
                        </button>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item" data-dz-remove>{{ __('Xóa tập tin') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </script>
@endpush
