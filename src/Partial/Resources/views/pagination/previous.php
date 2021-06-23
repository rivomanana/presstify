<?php
/**
 * Pagination - Lien vers la page précédente.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Partial\Partials\Pagination\PaginationView $this
 */
?>

<?php if ($this->getPage() > 1) : ?>
    <li class="PartialPagination-item PartialPagination-item--previous">
        <?php echo partial('tag', $this->get('links.previous')); ?>
    </li>
<?php endif;