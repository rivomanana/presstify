<?php declare(strict_types=1);

namespace tiFy\Partial;

use tiFy\Container\ServiceProvider;
use tiFy\Contracts\Partial\{
    Accordion as AccordionContract,
    Breadcrumb as BreadcrumbContract,
    CookieNotice as CookieNoticeContract,
    Dropdown as DropdownContract,
    Holder as HolderContract,
    ImageLightbox as ImageLightboxContract,
    Modal as ModalContract,
    Notice as NoticeContract,
    Pagination as PaginationContract,
    Partial as PartialContract,
    PartialFactory,
    PdfPreview as PdfPreviewContract,
    Sidebar as SidebarContract,
    Slider as SliderContract,
    Spinner as SpinnerContract,
    Tab as TabContract,
    Table as TableContract,
    Tag as TagContract};
use tiFy\Partial\Partials\{
    Accordion\Accordion,
    Breadcrumb\Breadcrumb,
    CookieNotice\CookieNotice,
    Dropdown\Dropdown,
    Holder\Holder,
    ImageLightbox\ImageLightbox,
    Modal\Modal,
    Notice\Notice,
    Pagination\Pagination,
    PdfPreview\PdfPreview,
    Sidebar\Sidebar,
    Slider\Slider,
    Spinner\Spinner,
    Tab\Tab,
    Table\Table,
    Tag\Tag};

class PartialServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'partial',
        'partial.viewer',
        AccordionContract::class,
        BreadcrumbContract::class,
        CookieNoticeContract::class,
        DropdownContract::class,
        HolderContract::class,
        ImageLightboxContract::class,
        ModalContract::class,
        NoticeContract::class,
        PaginationContract::class,
        PdfPreviewContract::class,
        SidebarContract::class,
        SliderContract::class,
        SpinnerContract::class,
        TabContract::class,
        TableContract::class,
        TagContract::class
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('partial', function () {
            return new Partial($this->getContainer());
        });

        $this->registerFactories();

        $this->registerViewer();
    }

    /**
     * Déclaration des controleurs de portions d'affichage.
     *
     * @return void
     */
    public function registerFactories(): void
    {
        $this->getContainer()->add(AccordionContract::class, function () {
            return new Accordion();
        });

        $this->getContainer()->add(BreadcrumbContract::class, function () {
            return new Breadcrumb();
        });

        $this->getContainer()->add(CookieNoticeContract::class, function () {
            return new CookieNotice();
        });

        $this->getContainer()->add(DropdownContract::class, function () {
            return new Dropdown();
        });

        $this->getContainer()->add(HolderContract::class, function () {
            return new Holder();
        });

        $this->getContainer()->add(ImageLightboxContract::class, function () {
            return new ImageLightbox();
        });

        $this->getContainer()->add(ModalContract::class, function () {
            return new Modal();
        });

        $this->getContainer()->add(NoticeContract::class, function () {
            return new Notice();
        });

        $this->getContainer()->add(PaginationContract::class, function () {
            return new Pagination();
        });

        $this->getContainer()->add(PdfPreviewContract::class, function () {
            return new PdfPreview();
        });

        $this->getContainer()->add(SidebarContract::class, function () {
            return new Sidebar();
        });

        $this->getContainer()->add(SliderContract::class, function () {
            return new Slider();
        });

        $this->getContainer()->add(SpinnerContract::class, function () {
            return new Spinner();
        });

        $this->getContainer()->add(TabContract::class, function () {
            return new Tab();
        });

        $this->getContainer()->add(TableContract::class, function () {
            return new Table();
        });

        $this->getContainer()->add(TagContract::class, function () {
            return new Tag();
        });
    }

    /**
     * Déclaration du controleur d'affichage.
     *
     * @return void
     */
    public function registerViewer(): void
    {
        $this->getContainer()->add('partial.viewer', function (PartialFactory $factory) {
            /** @var PartialContract $manager */
            $manager = $this->getContainer()->get('partial');

            $directory = $factory->get(
                'viewer.directory',
                $manager->resourcesDir("/views/{$factory->getAlias()}")
            );
            $override_dir = $factory->get('viewer.override_dir');

            return view()
                ->setDirectory(is_dir($directory) ? $directory : null)
                ->setController(PartialView::class)
                ->setOverrideDir((($override_dir) && is_dir($override_dir))
                    ? $override_dir
                    : (is_dir($directory) ? $directory : __DIR__)
                )
                ->set('partial', $factory);
        });
    }
}