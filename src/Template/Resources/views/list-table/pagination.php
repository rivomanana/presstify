<?php
/**
 * Interface de pagination de la table.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Template\Templates\ListTable\Viewer $this
 * @var string $which top|bottom.
 */
?>
<?php if ($total = $this->pagination()->getTotal()) : ?>
    <?php $pagination = $this->pagination()->which($which ?? 'top'); ?>
    <div <?php echo $this->htmlAttrs($pagination->get('attrs', [])); ?>>
        <span class="displaying-num">
            <?php
            printf(
                _n(
                    '%s élément',
                    '%s éléments',
                    $total,
                    'tify'
                ),
                number_format_i18n($total)
            );
            ?>
        </span>
        <span class="pagination-links<?php echo $pagination->isInfiniteScroll() ? ' hide-if-js' : ''; ?>">
            <?php echo $pagination->firstPage(); ?>
            <?php echo $pagination->prevPage(); ?>
            <?php echo $pagination->currentPage(); ?>
            <?php echo $pagination->nextPage(); ?>
            <?php echo $pagination->lastPage(); ?>
        </span>
    </div>
<?php endif;