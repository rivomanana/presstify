<?php declare(strict_types=1);

namespace tiFy\Wordpress\Contracts;

use tiFy\Contracts\Support\ParamsBag;
use tiFy\Support\DateTime;
use tiFy\Wordpress\Contracts\Database\PostBuilder;
use WP_Post;
use WP_Term;
use WP_User;

interface QueryPost extends ParamsBag
{
    /**
     * Récupération d'une instance basée sur le post global courant.
     *
     * @return static|null
     */
    public static function createFromGlobal(): ?QueryPost;

    /**
     * Récupération d'une instance basée sur l'identifiant de qualification d'un post.
     *
     * @param int $post_id Identifiant de qualification.
     *
     * @return static|null
     */
    public static function createFromId($post_id): ?QueryPost;

    /**
     * Récupération d'une instance basée sur le nom de qualification d'un post.
     *
     * @param string $post_name Nom de qualification
     *
     * @return static|null
     */
    public static function createFromName(string $post_name): ?QueryPost;

    /**
     * Récupération d'une instance basée sur une liste de données de post.
     *
     * @param array $postdata Liste des données de post. ID requis.
     *
     * @return static|null
     */
    public static function createFromPostdata(array $postdata): ?QueryPost;

    /**
     * Récupération de l'instance du modèle de base de donnée associé.
     *
     * @return PostBuilder
     */
    public function db(): PostBuilder;

    /**
     * Récupération de l'identifiant de qualification de l'auteur original.
     *
     * @return int
     */
    public function getAuthorId();

    /**
     * Récupération de la liste des classes associées.
     *
     * @param string[] $classes Liste de classes complémentaires.
     * @param bool $html Activation du format de sortie de l'attribut de balise class. ex. class="post"
     *
     * @return string|array
     */
    public function getClass(array $classes = [], bool $html = true);

    /**
     * Récupération d'un commentaire associé.
     *
     * @param int $id Identifiant de qualification du commentaire.
     *
     * @return QueryComment|null
     */
    public function getComment(int $id): ?QueryComment;

    /**
     * Récupération de la liste des commentaires associé.
     * @see https://codex.wordpress.org/Class_Reference/WP_Comment_Query
     *
     * @param array $args Liste des argument de récupération.
     *
     * @return QueryComments|QueryComment[]|null
     */
    public function getComments(array $args = []): iterable;

    /**
     * Récupération du contenu de description.
     *
     * @param bool $raw Formatage de la valeur.
     *
     * @return string
     */
    public function getContent(bool $raw = false);

    /**
     * Récupération de la date de création au format datetime.
     *
     * @param bool $gmt Activation de la valeur basée sur le temps moyen de Greenwich.
     *
     * @return string
     */
    public function getDate(bool $gmt = false);

    /**
     * Récupération de l'objet DateTime basée sur la date création.
     *
     * @param bool $gmt Activation de la valeur basée sur le temps moyen de Greenwich.
     *
     * @return DateTime
     */
    public function getDateTime(bool $gmt = false): DateTime;

    /**
     * Récupération du lien d'édition du post dans l'interface administrateur.
     *
     * @return string
     */
    public function getEditLink();

    /**
     * Récupération de la valeur brute ou formatée de l'extrait.
     *
     * @param bool $raw Formatage de la valeur.
     *
     * @return string
     */
    public function getExcerpt(bool $raw = false);

    /**
     * Récupération de l'identifiant unique de qualification global.
     * @internal Ne devrait pas être utilisé en tant que lien.
     * @see https://developer.wordpress.org/reference/functions/the_guid/
     *
     * @return string
     */
    public function getGuid();

    /**
     * Récupération de l'identifiant de qualification Wordpress du post.
     *
     * @return int
     */
    public function getId();

    /**
     * Récupération d'une metadonnée.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param bool $single Type de metadonnés. single (true)|multiple (false). false par défaut.
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMeta(string $meta_key, $single = false, $default = null);

    /**
     * Récupération de la liste des indices de métadonnées.
     *
     * @param boolean $registered Indicateur de récupération de indices de metadonnés déclarés.
     *
     * @return array
     */
    public function getMetaKeys(bool $registered = true): array;

    /**
     * Récupération d'une metadonnée de type multiple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaMulti(string $meta_key, $default = null);

    /**
     * Récupération d'une metadonnée de type simple.
     *
     * @param string $meta_key Clé d'indexe de la metadonnée à récupérer
     * @param mixed $default Valeur de retour par défaut.
     *
     * @return mixed
     */
    public function getMetaSingle(string $meta_key, $default = null);

    /**
     * Récupération de la date de la dernière modification au format datetime.
     *
     * @param bool $gmt Activation de la valeur basée sur le temps moyen de Greenwich.
     *
     * @return string
     */
    public function getModified(bool $gmt = false);

    /**
     * Récupération de l'objet DateTime basée sur la date de modification.
     *
     * @param bool $gmt Activation de la valeur basée sur le temps moyen de Greenwich.
     *
     * @return DateTime
     */
    public function getModifiedDateTime(bool $gmt = false): DateTime;

    /**
     * Alias de récupération de l'identifiant de qualification Wordpress (post_name).
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération de l'identifiant de qualification du post parent relatif.
     *
     * @return int
     */
    public function getParentId();

    /**
     * Récupération du chenmin relatif vers l'affichage du post dans l'interface utilisateur.
     *
     * @return string
     */
    public function getPath();

    /**
     * Récupération du permalien d'affichage du post dans l'interface utilisateur.
     *
     * @return string
     */
    public function getPermalink();

    /**
     * Récupération de l'object Post Wordpress associé.
     *
     * @return WP_Post
     *
     * @deprecated
     */
    public function getPost();

    /**
     * Récupération de l'identifiant de qualification Wordpress (post_name).
     *
     * @return string
     */
    public function getSlug();

    /**
     * Récupération du statut de publication.
     *
     * @return string
     */
    public function getStatus();

    /**
     * Récupération de la liste des termes de taxonomie.
     *
     * @param string|array $taxonomy Liste ou Nom de qualification de la taxonomie.
     * @param array $args Liste des arguments de récupération
     *
     * @return array|WP_Term[]
     */
    public function getTerms($taxonomy, $args = []);

    /**
     * Récupération de l'url de l'image représentative.
     *
     * @param string|array $size Taille d'image déclaré|Tableau indexé [hauteur, largeur].
     * @param array $attrs Liste des attributs HTML de la balise img
     *
     * @return string
     */
    public function getThumbnail($size = 'post-thumbnail', $attrs = []);

    /**
     * Récupération de l'url de l'image représentative.
     *
     * @param string|array $size Taille d'image déclaré|Tableau indexé [hauteur, largeur].
     *
     * @return string
     */
    public function getThumbnailUrl($size = 'post-thumbnail');

    /**
     * Récupération de la valeur brute ou formatée de l'intitulé de qualification.
     *
     * @param bool $raw Formatage de la valeur.
     *
     * @return string
     */
    public function getTitle(bool $raw = false);

    /**
     * Récupération du type de post.
     *
     * @return string
     */
    public function getType();

    /**
     * Récupération de l'instance de post Wordpress associée.
     *
     * @return WP_Post
     */
    public function getWpPost();

    /**
     * Vérification d'existance de terme(s) de taxonomie pour le post associé.
     *
     * @param string|int|array Nom de qualification|Identifiant de qualification|Slug du terme ou liste de terme.
     * @param string $taxonomy Nom de qualification de la taxonomie.
     *
     * @return boolean
     */
    public function hasTerm($term, string $taxonomy): bool;

    /**
     * Sauvegarde des données du post en base.
     *
     * @param array $postdata Liste des données à enregistrer
     *
     * @return void
     */
    public function save(array $postdata): void;

    /**
     * Sauvegarde (Ajout ou mise à jour) d'un commentaire associé au post.
     *
     * @param string $content Contenu du commentaire.
     * @param array $commentdata
     * @param WP_User|null $wp_user
     *
     * @return int
     */
    public function saveComment(string $content, array $commentdata = [], ?WP_User $wp_user = null): int;

    /**
     * Sauvegarde (Ajout ou mise à jour) de metadonnées du post en base.
     *
     * @param string|array $key Indice de métadonnées ou tableau associatif clé/valeur.
     * @param mixed $value Valeur de la métadonnées si key est un indice.
     *
     * @return void
     */
    public function saveMeta($key, $value = null): void;

    /**
     * Vérification de correspondance du type de post.
     *
     * @param array $post_types Liste des types de post à vérifier.
     *
     * @return bool
     */
    public function typeIn(array $post_types): bool;
}