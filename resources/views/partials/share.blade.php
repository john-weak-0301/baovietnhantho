<div class="modal modal-share" id="shareModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            {!! Share::page($link ?? url()->current(), 'Chia sẻ')
                ->facebook()
                ->twitter()
                ->pinterest()
                ->linkedin()
            !!}
        </div>
    </div>
</div>

<script>
  (function() {
    try {
      new bsn.Modal(document.getElementById('openShareModal'));
    } catch (e) {}
  })();
</script>
