<?php declare(strict_types=1);

namespace tiFy\Kernel;

use tiFy\Http\{Request, Response, Session, SessionFlashBag, Uri};
use tiFy\Container\ServiceProvider;
use tiFy\Kernel\{Events\Manager as EventsManager, Events\Listener, Logger\Logger, Notices\Notices};
use tiFy\Support\{ClassInfo, ParamsBag};

class KernelServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * @internal requis. Tous les noms de qualification de services à traiter doivent être renseignés.
     * @var string[]
     */
    protected $provides = [
        'class-info',
        'events',
        'events.listener',
        'logger',
        'notices',
        'params.bag',
        'request',
        'session',
        'uri'
    ];

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        if (!defined('TIFY_CONFIG_DIR')) {
            define('TIFY_CONFIG_DIR', get_template_directory() . '/config');
        }
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->getContainer()->share('path', function () {
            return new Path();
        });

        $this->getContainer()->share('class-loader', new ClassLoader($this->getContainer()));

        $this->getContainer()->share('config', new Config($this->getContainer()));
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->add('class-info', function ($class) {
            return new ClassInfo($class);
        });

        $this->getContainer()->share('events', function () {
            return new EventsManager();
        });

        $this->getContainer()->add('events.listener', function (callable $callback) {
            return new Listener($callback);
        });

        $this->getContainer()->add('logger', function ($name = null, $attrs = []) {
            return Logger::create($name, $attrs);
        });

        $this->getContainer()->add('notices', function () {
            return new Notices();
        });

        $this->getContainer()->add('params.bag', function (?array $attrs = []) {
            return is_array($attrs) ? ParamsBag::createFromAttrs($attrs) : new ParamsBag();
        });

        $this->getContainer()->share('request', function () {
            return Request::setFromGlobals();
        });

        $this->getContainer()->share('response', function () {
            return new Response();
        });

        $this->getContainer()->share('session', function () {
            $session = new Session($this->getContainer());

            if (session_status() == PHP_SESSION_NONE) {
                $session->start();
            }

            return $session->setContainer($this->getContainer());
        });

        $this->getContainer()->share('uri', function () {
            return Uri::createFromRequest($this->getContainer()->get('request'));
        });
    }
}