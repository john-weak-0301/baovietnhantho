<?php
    $context = new App\Utils\PageContext;
    $query = App\Model\Popup::query();
    if ($context->isHome()) {
        $query = $query->where('show_home_page', 1);
        ->where('show_home_page', $context->isHome());
    }
    if ($context->isNews()) {
        $query = $query->where('show_posts', 1);
    }
    if ($context->isPages()) {
        $query = $query->where('show_pages', 1);
    }
    if ($context->isProducts()) {
        $query = $query->where('show_products', 1);
    }
    if ($context->isService()) {
        $query = $query->where('show_service', 1);
    }
    if ($context->isExpert()) {
        $query = $query->where('show_expert', 1);
    }

    $popups = $query->get();
?>
