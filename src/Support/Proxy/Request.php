<?php declare(strict_types=1);

namespace tiFy\Support\Proxy;

//use tiFy\Contracts\Http\Request as RequestContract;

/**
 * @method static mixed all(array|mixed|null $keys = null)
 * @method static string|array|null cookie(string|null $key = null, string|array|null $default = null)
 * @method static string getBaseUrl()
 * @method static string getBasePath()
 * @method static string getHost()
 * @method static string getMethod()
 * @method static string getPathInfo()
 * @method static bool hasHeader(string $key)
 * @method static string|array|null header(string|null $key = null, string|array|null $default = null)
 * @method static mixed input(string|null $key = null, $default = null)
 * @method static boolean isMethod(string $method)
 * @method static boolean isSecure()
 * @method static string method()
 * @method static string|array|null server(string|null $key = null, string|array|null $default = null)
 */
class Request extends AbstractProxy
{
    public static function getInstanceIdentifier()
    {
        return 'request';
    }
}