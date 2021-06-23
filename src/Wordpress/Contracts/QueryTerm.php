<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Wordpress\Contracts\Database\TaxonomyBuilder;
use WP_Term;

interface QueryTerm extends ParamsBag
{
    /**
     * Récupération d'une instance basée sur l'identifiant de qualification du terme.
     *
     * @param int $term_id
     *
     * @return static|null
     */
    public static function createFromId(int $term_id): ?QueryTerm;

    /**
     * Récupération d'une instance basée sur le nom de qualification du terme.
     *
     * @param string $term_slug
     * @param string $taxonomy Nom de qualification de la taxonomie associée.
     *
     * @return static|null
     */
    public static function createFromSlug(string $term_slug, string $taxonomy): ?QueryTerm;

    /**
     * Récupération de l'instance du modèle de base de donnée associé.
     *
     * @return TaxonomyBuilder
     */
    public function db(): TaxonomyBuilder;

    /**
     * Récupération de la description.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Récupération de l'identifiant de qualification Wordpress du terme.
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Récupération d'une metadonnée.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param bool $single Type de metadonnés. single (true)|multiple (false). false par défaut.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMeta($meta_key, $single = false, $default = null);

    /**
     * Récupération d'une metadonnée de type multiple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaMulti($meta_key, $default = null);

    /**
     * Récupération d'une metadonnée de type simple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaSingle($meta_key, $default = null);

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération du permalien d'affichage de la liste de élément associés au terme.
     *
     * @return string
     */
    public function getPermalink(): string;

    /**
     * Récupération du nom de qualification Wordpress du terme.
     *
     * @return string
     */
    public function getSlug(): string;

    /**
     * Récupération de la taxonomie relative.
     *
     * @return string
     */
    public function getTaxonomy(): string;

    /**
     * Récupération de l'object Terme Wordpress associé.
     *
     * @return WP_Term
     */
    public function getWpTerm(): WP_Term;

    /**
     * Sauvegarde des données du terme en base.
     *
     * @param array $postdata Liste des données à enregistrer.
     *
     * @return void
     */
    public function save(array $termdata): void;

    /**
     * Sauvegarde (Ajout ou mise à jour) de metadonnées du terme en base.
     *
     * @param string|array $key Indice de métadonnées ou tableau associatif clé/valeur.
     * @param mixed $value Valeur de la métadonnées si key est un indice.
     *
     * @return void
     */
    public function saveMeta($key, $value = null): void;
}