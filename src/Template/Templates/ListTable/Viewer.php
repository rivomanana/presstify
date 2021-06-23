<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Contracts\Template\{FactoryLabels, FactoryParams};
use tiFy\Template\Factory\FactoryViewer;
use tiFy\Template\Templates\ListTable\Contracts\{
    BulkActions,
    Columns,
    Items,
    Pagination,
    Builder,
    Search,
    ViewFilters};

/**
 * @method Builder builder()
 * @method BulkActions bulkActions()
 * @method Columns columns()
 * @method Items items()
 * @method FactoryLabels|string label(?string $key = null, string $default = '')
 * @method string name()
 * @method Pagination pagination()
 * @method FactoryParams|mixed param($key = null, $default = null)
 * @method Search search()
 * @method ViewFilters viewFilters()
 */
class Viewer extends FactoryViewer
{
    /**
     * Instance du gabarit associé.
     * @var ListTable
     */
    protected $factory;

    /**
     * @inheritdoc
     */
    public function boot()
    {
        parent::boot();

        array_push(
            $this->mixins,
            'builder',
            'bulkActions',
            'columns',
            'items',
            'pagination',
            'row',
            'search',
            'viewFilters'
        );
    }
}