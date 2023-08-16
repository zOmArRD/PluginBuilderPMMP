<?php declare(strict_types=1);
/*
 * Created by PhpStorm
 *
 * User: zOmArRD
 * Date: 16/8/2023
 *
 * Copyright (c) 2023. OMY TECHNOLOGY by <dev@zomarrd.me>
 */

namespace zOmArRD\Builder\Interface;

interface FileIteratorFilter
{
    public function accept(string $current): bool;
}
