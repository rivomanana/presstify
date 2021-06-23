<?php declare(strict_types=1);

namespace tiFy\Form;

use tiFy\Contracts\Form\FormFactory as FormFactoryContract;
use tiFy\Contracts\Form\FormManager;
use tiFy\Form\Factory\ResolverTrait;
use tiFy\Support\ParamsBag;

class FormFactory extends ParamsBag implements FormFactoryContract
{
    use ResolverTrait;

    /**
     * Liste des instances de formulaire démarré.
     * @var FormFactory[]
     */
    private static $instance = [];

    /**
     * Indicateur de traitement automatique.
     * @var boolean|null
     */
    protected $auto;

    /**
     * Instance du gestionnaire de formulaire.
     * @var string
     */
    protected $manager = '';

    /**
     * Nom de qualification du formulaire.
     * @var string
     */
    protected $name = '';

    /**
     * Indicateur de préparation.
     * @var boolean
     */
    protected $prepared = false;

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return (string)$this->render();
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {

    }

    /**
     * @inheritDoc
     */
    public function csrf(): string
    {
        return wp_create_nonce('Form' . $this->name());
    }

    /**
     * Listes des attributs de configuration par défaut.
     * @return array {
     *      @var string $action Propriété 'action' de la balise <form/>.
     *      @var array $addons Liste des attributs des addons actifs.
     *      @var array $attrs Liste des attributs complémentaires de la balise <form/>.
     *      @var string $after Post-affichage, après la balise <form/>.
     *      @var boolean $auto Indicateur de traitement automatisé de la requête de soumission du formulaire. true par défaut
     *      @var string $before Pré-affichage, avant la balise <form/>.
     *      @var array $buttons Liste des attributs des boutons actifs.
     *      @var string $enctype Propriété 'enctype' de la balise <form/>.
     *      @var array $events Liste des événements de court-circuitage.
     *      @var array $fields Liste des attributs de champs.
     *      @var boolean|array $grid Activation de l'agencement des éléments sur grille.
     *      @var string $method Propriété 'method' de la balise <form/>.
     *      @var array $notices Liste des attributs des messages de notification.
     *      @var array $options Liste des options du formulaire.
     *      @var string $title Intitulé de qualification du formulaire.
     *      @var array $viewer Attributs de configuration du gestionnaire de gabarits d'affichage.
     * }
     */
    public function defaults(): array
    {
        return [
            'action'  => '',
            'addons'  => [],
            'after'   => '',
            'attrs'   => [],
            'auto'    => true,
            'before'  => '',
            'buttons' => [],
            'enctype' => '',
            'events'  => [],
            'fields'  => [],
            'grid'    => false,
            'method'  => 'post',
            'notices' => [],
            'options' => [],
            'title'   => '',
            'viewer'  => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getAction(): string
    {
        return $this->get('action', '');
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->get('method', 'post');
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return $this->get('title') ?: $this->name();
    }

    /**
     * @inheritDoc
     */
    public function hasGrid()
    {
        return !empty($this->get('grid'));
    }

    /**
     * @inheritDoc
     */
    public function index()
    {
        return form()->index($this->name());
    }

    /**
     * @inheritDoc
     */
    public function isAuto(): bool
    {
        return $this->auto;
    }

    /**
     * @inheritDoc
     */
    public function isPrepared(): bool
    {
        return $this->prepared;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return strval($this->name);
    }

    /**
     * @inheritDoc
     */
    public function onSetCurrent(): void
    {
        $this->events('form.set.current', [&$this]);
    }

    /**
     * @inheritDoc
     */
    public function onSuccess(): bool
    {
        return request()->get('success') === $this->name();
    }

    /**
     * @inheritDoc
     */
    public function onResetCurrent(): void
    {
        $this->events('form.reset.current', [&$this]);
    }

    /**
     * @inheritDoc
     */
    public function prepare(): FormFactoryContract
    {
        if (!$this->isPrepared()) {
            $this->events('form.prepare', [&$this]);

            $this->boot();

            $this->parse();

            foreach ([
                         'events',
                         'addons',
                         'buttons',
                         'fields',
                         'groups',
                         'notices',
                         'options',
                         'request',
                         'session',
                         'validation',
                         'viewer'
                     ] as $service) {
                $this->resolve("factory.{$service}." . $this->name());
            }

            $this->groups()->prepare();
            foreach ($this->fields() as $field) {
                $field->prepare();
            }

            $this->prepared = true;

            $this->events('form.prepared', [&$this]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if (!$this->prepared) {
            $this->prepare();
        }

        $this->renderPrepare();

        $groups = $this->groups()->getGrouped();
        $fields = $this->fields();
        $buttons = $this->buttons();
        $notices = $this->notices()->getMessages();

        return (string)$this->viewer('form', compact('buttons', 'fields', 'groups', 'notices'));
    }

    /**
     * @inheritDoc
     */
    public function renderPrepare()
    {
        if (!$this->has('attrs.id')) {
            $this->set('attrs.id', "Form-content--{$this->name()}");
        }
        if (!$this->get('attrs.id')) {
            $this->pull('attrs.id');
        }

        $default_class = "Form-content Form-content--{$this->name()}";
        if (!$this->has('attrs.class')) {
            $this->set('attrs.class', $default_class);
        } else {
            $this->set('attrs.class', sprintf($this->get('attrs.class', ''), $default_class));
        }
        if (!$this->get('attrs.class')) {
            $this->pull('attrs.class');
        }

        $this->set('attrs.action', $this->getAction() .
            ($this->option('anchor') && ($id = $this->get('attrs.id'))
                ? "#{$id}" : '')
        );
        $this->set('attrs.method', $this->getMethod());
        if ($enctype = $this->get('enctype')) {
            $this->set('attrs.enctype', $enctype);
        }

        if ($grid = $this->get('grid')) {
            $grid = is_array($grid) ? $grid : [];

            $this->set("attrs.data-grid", 'true');
            $this->set("attrs.data-grid_gutter", $grid['gutter'] ?? 0);
        }

        if ($this->onSuccess()) {
            $this->notices()->add('success', $this->notices()->params('success.message'));

            asset()->setInlineJs(
                'if (window.history && window.history.replaceState){' .
                'let anchor=window.location.href.split("#")[1],' .
                'location=window.location.href.split("?")[0] + (anchor ? "#" + anchor : "");' .
                'window.history.pushState("", document.title, location);};',
                true
            );
        }

        foreach ($this->fields() as $field) {
            $field->renderPrepare();
        }
    }

    /**
     * @inheritDoc
     */
    public function setInstance(string $name, FormManager $manager): FormFactoryContract
    {
        if (!isset(self::$instance[$name])) {
            self::$instance[$name] = $this;

            $this->name = $name;
            $this->manager = $manager;
            $this->form = $this;

            if (is_null($this->auto)) {
                $this->auto = (bool)$this->get('auto', true);
            }

            app()->share("form.factory.events.{$this->name}", function () {
                return $this->resolve('factory.events', [$this->get('events', []), $this]);
            });

            app()->share("form.factory.addons.{$this->name}", function () {
                return $this->resolve('factory.addons', [$this->get('addons', []), $this]);
            });

            app()->share("form.factory.buttons.{$this->name}", function () {
                return $this->resolve('factory.buttons', [$this->get('buttons', []), $this]);
            });

            app()->share("form.factory.fields.{$this->name}", function () {
                return $this->resolve('factory.fields', [$this->get('fields', []), $this]);
            });

            app()->share("form.factory.groups.{$this->name}", function () {
                return $this->resolve('factory.groups', [$this->get('groups', []), $this]);
            });

            app()->share("form.factory.notices.{$this->name}", function () {
                return $this->resolve('factory.notices', [$this->get('notices', []), $this]);
            });

            app()->share("form.factory.options.{$this->name}", function () {
                return $this->resolve('factory.options', [$this->get('options', []), $this]);
            });

            app()->share("form.factory.request.{$this->name}", function () {
                return $this->resolve('factory.request', [$this]);
            });

            app()->share("form.factory.session.{$this->name}", function () {
                return $this->resolve('factory.session', [$this]);
            });

            app()->share("form.factory.validation.{$this->name}", function () {
                return $this->resolve('factory.validation', [$this]);
            });

            app()->share("form.factory.viewer.{$this->name}", function () {
                return $this->resolve('factory.viewer', [$this]);
            });

            $this->events('form.init', [&$this]);
        }
        return $this;
    }
}