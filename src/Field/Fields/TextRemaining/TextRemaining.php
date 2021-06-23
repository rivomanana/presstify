<?php declare(strict_types=1);

namespace tiFy\Field\Fields\TextRemaining;

use tiFy\Contracts\Field\{FieldFactory as FieldFactoryContract, TextRemaining as TextRemainingContract};
use tiFy\Field\FieldFactory;

class TextRemaining extends FieldFactory implements TextRemainingContract
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
     *      @var string $selector Type de selecteur. textarea (défaut)|input.
     *      @var int $max Nombre maximum de caractères attendus. 150 par défaut.
     *      @var boolean $limit Activation de la limite de saisie selon le nombre maximum de caractères.
     *  }
     */
    public function defaults(): array
    {
        return [
            'attrs'    => [],
            'after'    => '',
            'before'   => '',
            'name'     => '',
            'value'    => '',
            'viewer'   => [],
            'limit'    => false,
            'max'      => 150,
            'selector' => 'textarea'
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): FieldFactoryContract
    {
        parent::parse();

        $this->set(
            'attrs.class',
            trim(
                sprintf(
                    $this->get('attrs.class', '%s'),
                    ' FieldTextRemaining FieldTextRemaining--' . $this->get('selector')
                )
            )
        );

        $this->set('attrs.data-id', $this->getId());

        $this->set('attrs.data-control', 'text-remaining');

        $this->set('tag', $this->get('selector'));

        $this->set(
            'attrs.data-options',
            [
                'infos' => [
                    'plural'   => __('restants', 'tify'),
                    'singular' => __('restant', 'tify'),
                    'none'     => __('restant', 'tify'),
                ],
                'limit' => $this->get('limit'),
                'max'   => $this->get('max')
            ]
        );

        switch($this->get('tag')) {
            case 'textarea' :
                $this->set('content', $this->get('value'));
                break;
            case 'input' :
                $this->set('attrs.value', $this->get('value'));
                break;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseDefaults(): FieldFactoryContract
    {
        $this->parseName();
        $this->parseViewer();

        return $this;
    }
}