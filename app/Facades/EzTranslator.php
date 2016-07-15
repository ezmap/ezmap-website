<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * User: billythekid
 * Date: 15/07/2016
 * Time: 13:10
 */
class EzTranslator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'eztrans';
    }
}