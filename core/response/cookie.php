<?php

namespace Core\Response;

class Cookie
{

    const ENCRYPT_KEY = '3d5e1057a55199aba24cd4fd6c7c1bb9';

    public static function set($name, $value, $expire = 0)
    {
        $name = self::encryptName($name);


    }

    protected static function encryptName($name)
    {
        $str = $name . self::ENCRYPT_KEY;

        return md5($str);
    }

}