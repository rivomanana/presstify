<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\FactoryNotices as FactoryNoticesContract;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Kernel\Notices\Notices;

class FactoryNotices extends Notices implements FactoryNoticesContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;
}