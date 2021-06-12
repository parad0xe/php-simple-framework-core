<?php


namespace Parad0xeSimpleFramework\Core\Http\Entity;


abstract class AbstractEntity
{
    public static ?string $TABLE;

    public function __construct()
    {
        if(self::$TABLE === null) {
            throw new \Exception("Entity (". self::class .") must define table name");
        }
    }
}
