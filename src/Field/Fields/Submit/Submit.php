<?php declare(strict_types=1);

namespace tiFy\Field\Fields\Submit;

use tiFy\Contracts\Field\{FieldFactory as FieldFactoryContract, Submit as SubmitContract};
use tiFy\Field\FieldFactory;

class Submit extends FieldFactory implements SubmitContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var string $name Clé d'indice de la valeur de soumission du champ.
     *      @var string $value Valeur courante de soumission du champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'  => [],
            'after'  => '',
            'before' => '',
            'name'   => '',
            'value'  => __('Envoyer', 'tify'),
            'viewer' => []
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        $this->set('attrs.type', 'submit');

        return $this;
    }
}