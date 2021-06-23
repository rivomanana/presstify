<?php declare(strict_types=1);

namespace tiFy\Partial\Partials\Sidebar;

use Closure;
use Illuminate\Support\Collection;
use tiFy\Contracts\Partial\{PartialFactory as PartialFactoryContract, Sidebar as SidebarContract};
use tiFy\Partial\PartialFactory;

/**
 * RESSOURCES POUR EVOLUTION :
 * @see http://mango.github.io/slideout/
 * @see http://webdesignledger.com/web-design-2/best-practices-for-hamburger-menus
 * http://tympanus.net/Blueprints/SlidePushMenus/
 * http://tympanus.net/Development/OffCanvasMenuEffects/
 * http://tympanus.net/Development/MultiLevelPushMenu/
 */
class Sidebar extends PartialFactory implements SidebarContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     *      @var array $attrs Attributs HTML du champ.
     *      @var string $after Contenu placé après le champ.
     *      @var string $before Contenu placé avant le champ.
     *      @var array $viewer Liste des attributs de configuration du pilote d'affichage.
     *      @var string|int $width Largeur de l'interface en px ou en %. Si l'unité de valeur n'est pas renseignée
     *                             l'unité par défault est le px.
     *      @var int $z -index Profondeur de champs.
     *      @var array $attrs Liste des attributs HTML.
     *      @var string $pos Position de l'interface left (default)|right.
     *      @var bool $closed Etat de fermeture initial de l'interface.
     *      @var bool $outside_close Fermeture au clic en dehors de l'interface.
     *      @var bool $animate Activation de l'animation à l'ouverture et la fermeture.
     *      @var bool|string $toggle Activation et contenu de bouton de bascule. Si la valeur booléene active ou
     *                               désactive le bouton; la valeur chaîne de caractère active et affiche la chaîne.
     *                               ex : <span>X</span>.
     *      @var string|int $min-width Largeur de la fenêtre du navigateur en px ou %, à partir de laquelle l'interface
     *                                 est active. Si l'unité de valeur n'est pas renseignée l'unité par défault est le
     *                                 px.
     *      @var SidebarItem[]|array $items {
     *          Liste des élements.
     *          @var string $name Nom de qualification
     *          @var string|callable $content Contenu
     *          @var array $attrs Liste des attributs HTML du conteneur.
     *          @var int $position Position de l'élément.
     *      }
     *      @var boolean|string $header Contenu de l'entête de l'interface.
     *      @var boolean|string $footer Contenu du pied de l'interface.
     *      @var string $theme Theme couleur de l'interface light|dark.
     * }
     */
    public function defaults(): array
    {
        return [
            'attrs'         => [],
            'after'         => '',
            'before'        => '',
            'viewer'        => [],
            'width'         => '300px',
            'z-index'       => 99990,
            'pos'           => 'left',
            'closed'        => true,
            'outside_close' => true,
            'animate'       => true,
            'min-width'     => '991px',
            'items'         => [],
            'header'        => true,
            'footer'        => true,
            'toggle'        => true,
            'theme'         => 'light',
        ];
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialFactoryContract
    {
        parent::parse();

        $this->set(
            'attrs.style',
            'width:' . $this->get('width') . ';z-index:' . $this->get('z-index') . $this->get('attrs.style', '')
        )
            ->set('attrs.data-control', 'sidebar')
            ->set('attrs.aria-animate', $this->get('animate') ? 'true' : 'false')
            ->set('attrs.aria-closed', $this->get('closed') ? 'true' : 'false')
            ->set('attrs.aria-outside_close', $this->get('outside_close') ? 'true' : 'false')
            ->set('attrs.aria-position', $this->get('pos'))
            ->set('attrs.aria-theme', $this->get('theme'));

        $items = [];
        foreach ($this->get('items', []) as $item) {
            if ($item instanceof SidebarItem) {
                $items[] = $item;
            } elseif (is_array($item)) {
                $items[] = new SidebarItem($item);
            } elseif (is_string($item)) {
                $item = ['content' => $item];
                $items[] = new SidebarItem($item);
            }
        }

        $header = $this->get('header');
        $this->set('header', $header instanceof Closure
            ? call_user_func($header) : (is_string($header) ? $header : '&nbsp;'));

        $footer = $this->get('footer');
        $this->set('footer', $footer instanceof Closure
            ? call_user_func($footer) : (is_string($footer) ? $footer : '&nbsp;'));

        $this->set('items', (new Collection($items))->sortBy('position')->all());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseDefaults(): PartialFactoryContract
    {
        $default_class = 'Sidebar Sidebar--' . $this->getIndex();
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf(
                $this->get('attrs.class', ''),
                $default_class
            ));
        }

        if (!$this->get('attrs.class')) {
            $this->pull('attrs.class');
        }

        foreach($this->get('view', []) as $key => $value) {
            $this->viewer()->set($key, $value);
        }

        return $this;
    }
}