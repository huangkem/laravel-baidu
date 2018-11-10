<?php

/*
 * This file is part of the liqunx/laravel-baidu.
 *
 * Copyright (c) 2018 Liqunx
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Liqunx\LaravelBaidu;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/** * Class Facade.
 *
 * @author liqunx
 */
class Facade extends LaravelFacade
{
    /**
     * 默认为 AIP 人工智能模块.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'baidu.aip';
    }

    /**
     * @return \Liqunx\Baidu\Ai\Application
     */
    public static function aip($name = '')
    {
        return $name ? app('baidu.aip.'.$name) : app('baidu.aip');
    }
}
