<div class="input-group" data-controller="clipboard" style="max-width: 350px;">
    <input type="text"
           class="form-control bg-white text-gray-dark"
           data-target="clipboard.source"
           data-action="focus->clipboard#select"
           readonly="readonly"
           value="{{ $media->disk === 'public' ? $media->getUrl() : $media->getFullUrl() }}">

    <span class="input-group-append">
		<button class="btn btn-default" type="button" data-action="clipboard#copy">Copy</button>
    </span>
</div>
