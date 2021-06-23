<?php declare(strict_types=1);

namespace tiFy\Contracts\Partial;

interface Tab extends PartialFactory
{
    /**
     * Mise à jour de l'onglet courant via une requête XHR.
     *
     * @return void
     */
    public function xhrSetTab();
}