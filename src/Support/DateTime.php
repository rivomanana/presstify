<?php declare(strict_types=1);

namespace tiFy\Support;

use Carbon\Carbon;
use DateTimeZone;
use Exception;

class DateTime extends Carbon
{
    /**
     * Instance du fuseau horaire utilisé par défaut.
     * @var DateTimeZone
     */
    protected static $GlobalTimeZone;

    /**
     * DateTime constructor.
     *
     * @param string|null $time
     * @param null|DateTimeZone $tz
     *
     * @return void
     *
     * @throws Exception
     */
    public function __construct($time = null, $tz = null)
    {
        if (is_null($tz)) {
            $tz = self::getGlobalTimeZone();
        }
        parent::__construct($time, $tz);
    }

    /**
     * @inheritDoc
     */
    public static function createFromFormat($format, $time, $tz=null)
    {
        return parent::createFromFormat($format, $time, is_null($tz) ? self::getGlobalTimeZone() : $tz);
    }

    /**
     * Récupération du fuseau horaire par défaut.
     *
     * @return DateTimeZone
     */
    public static function getGlobalTimeZone()
    {
        return self::$GlobalTimeZone ? : self::setGlobalTimeZone();
    }

    /**
     * Définition du fuseau horaire par défaut.
     *
     * @param DateTimeZone|null $tz
     *
     * @return DateTimeZone
     */
    public static function setGlobalTimeZone(?DateTimeZone $tz = null)
    {
        return self::$GlobalTimeZone = $tz ?: new DateTimeZone((request()->server('TZ') ?: 'UTC'));
    }
}