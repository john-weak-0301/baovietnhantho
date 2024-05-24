@push('modals-container')
    @foreach($manyForms as $key => $modal)
        <div
            id="screen-modal-{{ $key }}"
            class="modal"
            role="dialog"
            aria-hidden="true"
            aria-labelledby="screen-modal-{{ $key }}"
            data-controller="screen--modal"
            data-screen--modal-slug="{{ $templateSlug }}"
            data-screen--modal-async="{{ $templateAsync }}"
            data-screen--modal-method="{{ $templateAsyncMethod }}"
            data-screen--modal-url="{{ current_screen_action() }}">
            <div id="screen-modal-type-{{ $key }}" class="modal-dialog {{ $compose['class'] ?? '' }}" role="document">
                <form
                    method="POST"
                    id="screen-modal-form-{{ $key }}"
                    class="modal-content bg-lighter"
                    enctype="multipart/form-data"
                    data-controller="layouts--form"
                    data-action="layouts--form#submit"
                    data-layouts--form-button-animate="#submit-modal-{{ $key }}"
                    data-layouts--form-button-text="{{ __('Loading...') }}">
                    <div class="modal-header">
                        <h4 class="modal-title" data-target="screen--modal.title"></h4>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="hidden">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @csrf

                        <div data-async>
                            @foreach($modal as $item)
                                {!! optional($item)->render() !!}
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">
                            {{ __('Đóng') }}
                        </button>

                        <button type="submit" class="btn btn-primary" id="submit-modal-{{ $key }}">
                            {{__('Apply')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endpush
