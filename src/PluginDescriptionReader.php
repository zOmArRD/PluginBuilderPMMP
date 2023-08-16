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

use zOmArRD\Builder\Exception\PluginBuilderException;

class PluginDescriptionReader
{
    /**
     * Read plugin.yml
     *
     * @param string $pluginPath
     *
     * @return array
     */
    public static function read(string $pluginPath): array
    {
        $pluginDescription = yaml_parse_file($pluginPath . '/plugin.yml');

        if ($pluginDescription === false) {
            throw new PluginBuilderException('Unable to read plugin.yml');
        }

        if (!is_array($pluginDescription)) {
            throw new PluginBuilderException('plugin.yml must be an array');
        }

        return $pluginDescription;
    }
}
