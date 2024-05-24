<div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
        </svg>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{ route('platform.import_funds.update', ['id' => $id])."?status=importing" }}">Importing</a>
        <a class="dropdown-item" href="{{ route('platform.import_funds.update', ['id' => $id])."?status=pending" }}">Pending</a>
        <a class="dropdown-item" href="{{ route('platform.import_funds.update', ['id' => $id])."?status=approved" }}">Approved</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item delete-fund-import" data-href="{{ route('platform.import_funds.delete', ['id' => $id]) }}">Xóa</a>
    </div>
</div>

<style type="text/css">
    .table-responsive {
        overflow-x: unset !important;
    }
</style>

<script type="text/javascript">
    $('.delete-fund-import').on('click', function(evt) {
        let text = "Bạn có muốn xóa?";
        if (confirm(text) == true) {
            window.location.href = $(this).data("href");
        }
    });
</script>
