<?php declare(strict_types=1);

namespace tiFy\Template\Factory;

use tiFy\Contracts\Template\FactoryRequest as FactoryRequestContract;
use tiFy\Contracts\Template\TemplateFactory;
use tiFy\Http\Request;

class FactoryRequest extends Request implements FactoryRequestContract
{
    use FactoryAwareTrait;

    /**
     * Instance du gabarit d'affichage.
     * @var TemplateFactory
     */
    protected $factory;
}