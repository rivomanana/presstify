<?php
/**
 * Vue ListTable.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 */
?>
<div class="wrap">
    <?php $this->insert('header'); ?>
    <?php $this->insert('view-filters'); ?>
    <form method="get" action="">
        <?php if ($this->param('search') && ($this->items()->exists() || $this->request()->input('s'))) : ?>
            <?php $this->insert('search'); ?>
        <?php endif; ?>
        <?php $this->insert('table'); ?>
    </form>
</div>