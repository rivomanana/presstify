<?php declare(strict_types=1);

namespace tiFy\Template\Templates\ListTable;

use tiFy\Support\HtmlAttrs;
use tiFy\Template\Templates\ListTable\Contracts\Column as ColumnContract;

class ColumnCb extends Column
{
    /**
     * Indice de l'entête courante.
     * @var int
     */
    protected static $headerIndex = 0;

    /**
     * @inheritdoc
     */
    public function header(bool $with_id = true): string
    {
        $classes = ['manage-column', "column-{$this->getName()}", 'check-column'];

        if ($this->isHidden()) {
            $classes[] = 'hidden';
        }

        $attrs = [];
        if ($with_id) {
            $attrs['id'] = $this->getName();
        }

        $attrs['class'] = join(' ', $classes);

        return (string)$this->factory->viewer('thead-col_cb', [
            'attrs' => HtmlAttrs::createFromAttrs($attrs),
            'index' => ++self::$headerIndex,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function isPrimary(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function parse(): ColumnContract
    {
        parent::parse();

        $this->pull('attrs.data-colname');

        $this->set('attrs.class', 'check-column');

        $this->set('attrs.scope', 'row');

        return $this;
    }
}