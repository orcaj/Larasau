<?php

namespace Riazxrazor\Payumoney;


use Illuminate\Support\Facades\Facade;

class PayumoneyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Payumoney::class;
    }
}