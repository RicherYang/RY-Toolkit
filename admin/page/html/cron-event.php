<?php $list_table->views(); ?>

<form id="posts-filter" method="get">
    <input type="hidden" name="page" value="ry-toolkit-cron" />
    <?php $list_table->search_box(__('Search cron', 'ry-toolkit'), 'cron'); ?>
</form>

<?php $list_table->display(); ?>
