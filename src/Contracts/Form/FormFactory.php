<?php declare(strict_types=1);

namespace tiFy\Contracts\Form;

use tiFy\Contracts\Support\ParamsBag;

interface FormFactory extends FactoryResolver, ParamsBag
{
    /**
     * Résolution de sortie de l'affichage du formulaire.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Initialisation du contrôleur.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Récupération de la chaîne de sécurisation du formulaire (CSRF).
     *
     * @return string
     */
    public function csrf(): string;

    /**
     * Récupération de l'action du formulaire (url).
     *
     * @return string
     */
    public function getAction(): string;

    /**
     * Récupération de la méthode de soumission du formulaire.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Récupération de l'intitulé de qualification du formulaire.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Vérification d'activation de l'agencement des éléments.
     *
     * @return boolean
     */
    public function hasGrid();

    /**
     * Récupération du numéro d'indice du formulaire.
     *
     * @return int|null
     */
    public function index();

    /**
     * Vérification d'activation automatisée.
     *
     * @return boolean
     */
    public function isAuto(): bool;

    /**
     * Vérification de préparation active.
     *
     * @return boolean
     */
    public function isPrepared(): bool;

    /**
     * Récupération du nom de qualification du formulaire.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Evénement de déclenchement à l'initialisation du formulaire en tant que formulaire courant.
     *
     * @return void
     */
    public function onSetCurrent(): void;

    /**
     * Evénement de déclenchement à la réinitialisation du formulaire courant du formulaire.
     *
     * @return void
     */
    public function onResetCurrent(): void;

    /**
     * Initialisation (préparation) du champ.
     *
     * @return static
     */
    public function prepare(): FormFactory;

    /**
     * Affichage.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Définition de l'instance.
     *
     * @param string $name Nom de qualification du formulaire.
     * @param FormManager $manager Instance du gestionnaire de formulaires.
     *
     * @return static
     */
    public function setInstance(string $name, FormManager $manager): FormFactory;
}