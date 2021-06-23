<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Holder;

use tiFy\Contracts\Partial\{Holder as HolderContract, PartialFactory as PartialFactoryContract};
use tiFy\Partial\PartialFactory;

class Holder extends PartialFactory implements HolderContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string $content Contenu de remplacement.
     *      @var int $width Rapport de largeur relatif à la hauteur.
     *      @var int $height Rapport de hauteur relatif à la largeur.
     *
     * }
     */
    public function defaults() : array
    {
        return [
            'attrs'         => [],
            'after'         => '',
            'before'        => '',
            'viewer'        => [],
            'content'          => '',
            'width'            => 100,
            'height'           => 100,
            // @todo supprimer gérer en CSS
            'background-color' => '#E4E4E4',
            // @todo supprimer gérer en CSS
            'foreground-color' => '#AAA',
            // @todo supprimer gérer en CSS
            'font-size'        => '1em',
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        $this->set('attrs.class', sprintf($this->get('attrs.class', '%s'), 'PartialHolder'));
        $this->set(
            'attrs.style',
            "background-color:{$this->get('background-color')};color:{$this->get('foreground-color')};" .
            "font-size:{$this->get('font-size')}\""
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseDefaults(): PartialFactoryContract
    {
        foreach($this->get('view', []) as $key => $value) {
            $this->viewer()->set($key, $value);
        }

        return $this;
    }
}