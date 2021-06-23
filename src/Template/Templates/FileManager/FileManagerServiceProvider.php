<?php declare(strict_types=1);

namespace tiFy\Template\Templates\FileManager;

use League\Flysystem\Adapter\Local;
use tiFy\Contracts\Filesystem\Filesystem as tiFyFilesystem;
use tiFy\Template\Factory\FactoryServiceProvider;
use tiFy\Template\Templates\FileManager\Contracts\{
    Ajax as AjaxContract,
    Breadcrumb,
    Cache,
    FileManager,
    FileCollection,
    HttpController,
    HttpXhrController,
    IconSet,
    FileInfo,
    Filesystem,
    FileTag,
    Params,
    Sidebar};
use tiFy\View\ViewEngine;

class FileManagerServiceProvider extends FactoryServiceProvider
{
    /**
     * Instance du gabarit d'affichage.
     * @var FileManager
     */
    protected $factory;

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        events()->listen('template.factory.prepared', function (string $name) {
            if ($name === $this->factory->name()) {
                $this->factory->ajax()->parse();
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactories(): void
    {
        parent::registerFactories();

        $this->registerFactoryAjax();
        $this->registerFactoryBreadcrumb();
        $this->registerFactoryCache();
        $this->registerFactoryFileCollection();
        $this->registerFactoryFileInfo();
        $this->registerFactoryFilesystem();
        $this->registerFactoryFileTag();
        $this->registerFactoryHttpController();
        $this->registerFactoryHttpXhrController();
        $this->registerFactoryIconSet();
        $this->registerFactoryParams();
        $this->registerFactorySidebar();
    }

    /**
     * Déclaration du controleur de gestion des requêtes ajax.
     *
     * @return void
     */
    public function registerFactoryAjax(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('ajax'), function () {
            $ctrl = $this->factory->get('providers.ajax');
            $ctrl = $ctrl instanceof AjaxContract
                ? $ctrl
                : $this->getContainer()->get(AjaxContract::class);

            $attrs = $this->factory->param('ajax', []);
            if (is_string($attrs)) {
                $attrs = [
                    'url'      => $attrs,
                    'dataType' => 'json',
                    'type'     => 'POST'
                ];
            }

            return $ctrl->setTemplateFactory($this->factory)->set(is_array($attrs) ? $attrs : []);
        });
    }

    /**
     * Déclaration du controleur de fil d'ariane.
     *
     * @return void
     */
    public function registerFactoryBreadcrumb(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('breadcrumb'), function () {
            $ctrl = $this->factory->get('providers.breadcrumb');
            $ctrl = $ctrl instanceof Breadcrumb
                ? $ctrl
                : $this->getContainer()->get(Breadcrumb::class);

            return $ctrl->setTemplateFactory($this->factory)->setPath();
        });
    }

    /**
     * Déclaration du controleur de cache.
     *
     * @return void
     */
    public function registerFactoryCache(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('cache'), function () {
            $ctrl = $this->factory->get('providers.cache');
            $ctrl = $ctrl instanceof Cache
                ? $ctrl
                : $this->getContainer()->get(Cache::class);

            $root = paths()->getCachePath('Template/' . $this->factory->name());
            $repo = $this->getContainer()->get(tiFyFilesystem::class, [new Local($root)]);

            return $ctrl->setTemplateFactory($this->factory)
                ->setSource($this->factory->filesystem())->setCache($repo);
        });
    }

    /**
     * Déclaration du controleur de gestion de liste de fichiers.
     *
     * @return void
     */
    public function registerFactoryFileCollection(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('file-collection'), function (array $files = []) {
            $ctrl = $this->factory->get('providers.file-collection');
            $ctrl = $ctrl instanceof FileCollection
                ? $ctrl
                : $this->getContainer()->get(FileCollection::class);

            return $ctrl->setTemplateFactory($this->factory)->set($files);
        });
    }

    /**
     * Déclaration du controleur d'informations fichier.
     *
     * @return void
     */
    public function registerFactoryFileInfo(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('file-info'), function (array $infos) {
            $ctrl = $this->factory->get('providers.file-info');
            $ctrl = $ctrl instanceof FileInfo
                ? $ctrl
                : $this->getContainer()->get(FileInfo::class, [$infos]);

            return $ctrl->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur de système de fichiers.
     *
     * @return void
     */
    public function registerFactoryFilesystem(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('filesystem'), function () {
            $ctrl = $this->factory->get('providers.filesystem');

            return $ctrl instanceof tiFyFilesystem
                ? $ctrl
                : $this->getContainer()->get(Filesystem::class, [$this->factory]);
        });
    }

    /**
     * Déclaration du controleur de mots clefs d'un fichier.
     *
     * @return void
     */
    public function registerFactoryFileTag(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('file-tag'), function (FileInfo $file) {
            $ctrl = $this->factory->get('providers.file-tag');
            $ctrl = $ctrl instanceof FileTag ? $ctrl : $this->getContainer()->get(FileTag::class);

            return $ctrl->setTemplateFactory($this->factory)->setFile($file);
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryHttpController(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('controller'), function () {
            $ctrl = $this->factory->get('providers.controller');
            $ctrl = $ctrl instanceof HttpController
                ? $ctrl
                : $this->getContainer()->get(HttpController::class);

            return $ctrl->setTemplateFactory($this->factory);
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryHttpXhrController(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('xhr'), function () {
            $ctrl = $this->factory->get('providers.xhr');
            $ctrl = $ctrl instanceof HttpXhrController
                ? $ctrl
                : $this->getContainer()->get(HttpXhrController::class);

            return $ctrl->setTemplateFactory($this->factory);
        });
    }

    /**
     * Déclaration du controleur de gestion des icones.
     *
     * @return void
     */
    public function registerFactoryIconSet(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('icon-set'), function () {
            $ctrl = $this->factory->get('providers.icon-set');
            $ctrl = $ctrl instanceof IconSet
                ? $ctrl
                : $this->getContainer()->get(IconSet::class);

            return $ctrl->setTemplateFactory($this->factory)->set($this->factory->param('icon', []))->parse();
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryParams(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('params'), function () {
            $ctrl = $this->factory->get('providers.params');
            $ctrl = $ctrl instanceof Params
                ? $ctrl
                : $this->getContainer()->get(Params::class);

            $attrs = $this->factory->get('params', []);

            return $ctrl->setTemplateFactory($this->factory)->set(is_array($attrs) ? $attrs : [])->parse();
        });
    }

    /**
     * Déclaration du controleur de barre latérale de contrôle.
     *
     * @return void
     */
    public function registerFactorySidebar(): void
    {
        $this->getContainer()->add($this->getFactoryAlias('sidebar'), function () {
            $ctrl = $this->factory->get('providers.sidebar');
            $ctrl = $ctrl instanceof Sidebar
                ? $ctrl
                : $this->getContainer()->get(Sidebar::class);

            return $ctrl->setTemplateFactory($this->factory);
        });
    }

    /**
     * @inheritDoc
     */
    public function registerFactoryViewer(): void
    {
        $this->getContainer()->share($this->getFactoryAlias('viewer'), function () {
            $params = $this->factory->get('viewer', []);

            if (!$params instanceof ViewEngine) {
                $viewer = new ViewEngine(array_merge([
                    'directory' => template()->resourcesDir('/views/file-manager')
                ], $params));
                $viewer->setController(Viewer::class);

                if (!$viewer->getOverrideDir()) {
                    $viewer->setOverrideDir(template()->resourcesDir('/views/file-manager'));
                }
            } else {
                $viewer = $params;
            }

            $viewer->set('factory', $this->factory);

            return $viewer;
        });
    }
}