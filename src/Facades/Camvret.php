<?php

namespace Daemswibowo\Camvret\Facades;

use Illuminate\Support\Facades\Facade;

class Camvret extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'camvret';
    }
}
