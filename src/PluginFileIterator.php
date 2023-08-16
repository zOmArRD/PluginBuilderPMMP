<?php declare(strict_types=1);
/*
 * Created by PhpStorm
 *
 * User: zOmArRD
 * Date: 16/8/2023
 *
 * Copyright (c) 2023. OMY TECHNOLOGY by <dev@zomarrd.me>
 */

namespace zOmArRD\Builder;

use FilterIterator;
use Iterator;
use SplFileInfo;
use zOmArRD\Builder\Interface\FileIteratorFilter;

class PluginFileIterator extends FilterIterator
{
    private string $pluginPath;
    private FileIteratorFilter $filter;

    public function __construct(Iterator $iterator, string $pluginPath, FileIteratorFilter $filter)
    {
        parent::__construct($iterator);
        $this->pluginPath = $pluginPath;
        $this->filter = $filter;
    }

    final public function accept(): bool
    {
        $current = $this->getInnerIterator()->current();
        if ($current instanceof SplFileInfo) {
            $current = $current->getPathname();
        }

        /** @var string $current */
        if (is_dir($current)) {
            return false;
        }

        $current = substr($current, strlen($this->pluginPath) + 1);

        if (DIRECTORY_SEPARATOR !== '/') {
            $current = str_replace(DIRECTORY_SEPARATOR, '/', $current);
        }

        return $this->filter->accept($current);
    }
}
