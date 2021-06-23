<?php
/**
 * Pagination - Lien vers la première page.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Partials\Pagination\PaginationView $this
 */
?>

<?php if ($this->getPage() > 1) : ?>
    <li class="PartialPagination-item PartialPagination-item--first">
        <?php echo partial('tag', $this->get('links.first')); ?>
    </li>
<?php endif;