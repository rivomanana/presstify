<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

use Psr\Container\ContainerInterface;
use tiFy\Contracts\Asset\Asset as AssetContract;

/**
 * @method static string footer()
 * @method static string header()
 * @method static ContainerInterface getContainer()
 * @method static string normalize(string $string)
 * @method static AssetContract setDataJs(string $key, mixed $value, $footer = true)
 * @method static AssetContract setInlineCss(string $css)
 * @method static AssetContract setInlineJs(string $js, bool $footer = false)
 * @method static string url(string $path)
 */
class Asset extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'asset';
    }
}