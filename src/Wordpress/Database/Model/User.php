<?php declare(strict_types=1);

namespace tiFy\Wordpress\Database\Model;

use Corcel\Model\User as CorcelUser;
use tiFy\Database\Concerns\ColumnsAwareTrait;
use tiFy\Wordpress\Contracts\Database\UserBuilder;
use tiFy\Wordpress\Database\Concerns\MetaFieldsAwareTrait;

/**
 * @method static Usermeta createMeta($key, $value = null)
 * @method static mixed getMeta(string $meta_key)
 * @method static UserBuilder hasMeta(string|array $meta_key, mixed|null $value, string $operator = '=')
 * @method static UserBuilder hasMetaLike(string $key, string $value),
 * @method static boolean saveMeta($key, $value = null)
 */
class User extends CorcelUser implements UserBuilder
{
    use ColumnsAwareTrait, MetaFieldsAwareTrait;

    /**
     * Cartographie des classes de gestion des métadonnées.
     * @var array
     */
    protected $builtInClasses = [
        Comment::class => CommentMeta::class,
        Post::class    => Postmeta::class,
        Term::class    => Termmeta::class,
        User::class    => Usermeta::class,
    ];

    /**
     * Nom de qualification de la connexion associé.
     * @var string
     */
    protected $connection = 'wp';
}