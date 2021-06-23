<?php declare(strict_types=1);

namespace tiFy\Wordpress\Proxy;

use tiFy\Support\Proxy\AbstractProxy;
use tiFy\Wordpress\Contracts\{PageHook as PageHookContract, PageHookItem};

/**
 * @method static array all()
 * @method static PageHookItem|null get(string $name)
 * @method static PageHookContract set(array|string $key, mixed $value = null)
 */
class PageHook extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'wp.page-hook';
    }
}