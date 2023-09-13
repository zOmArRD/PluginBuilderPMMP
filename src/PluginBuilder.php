<?php
/*
 * Created by PhpStorm
 *
 * User: zOmArRD
 * Date: 16/8/2023
 *
 * Copyright (c) 2023. OMY TECHNOLOGY by <dev@zomarrd.me>
 */

namespace zOmArRD\Builder;

use FilesystemIterator;
use Phar;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use zOmArRD\Builder\Exception\PluginBuilderException;
use zOmArRD\Builder\Interface\FileIteratorFilter;

class PluginBuilder
{

    protected string $pluginPath, $outputPath;
    protected array $pluginDescription;

    protected function __construct(string $pluginPath)
    {
        // Set error reporting to the max
        error_reporting(E_ALL | E_STRICT);

        // Check if phar is readonly
        if (ini_get('phar.readonly')) {
            throw new PluginBuilderException('Phar is readonly. Please set phar.readonly to 0 in php.ini');
        }

        // Set plugin path
        $this->pluginPath = $pluginPath;

        // Read plugin.yml
        $this->pluginDescription = PluginDescriptionReader::read($pluginPath);

        // Set output path
        $this->outputPath = $pluginPath . '/output/';
    }

    /**
     * It is responsible for creating the phar file
     *
     * @return void
     */
    public function build(): void
    {
        $pharOutput = $this->outputPath . $this->pluginDescription['name'] . '-' . $this->pluginDescription['version'] . '.phar';

        // Delete old phar file
        if (file_exists($pharOutput)) {
            unlink($pharOutput);
        }

        // Start creating phar file
        echo 'Creating Phar file...' . PHP_EOL;

        $start = microtime(true);
        $phar = new Phar($pharOutput);
        $phar->setSignatureAlgorithm(Phar::SHA1);
        $phar->setStub('<?php echo "created by zOmArRD"; __HALT_COMPILER();');
        $phar->startBuffering();

        $filter = new class() implements FileIteratorFilter {
            public function accept(string $current): bool
            {
                return $current === 'plugin.yml'
                    || str_starts_with($current, 'src/')
                    || str_starts_with($current, 'vendor/')
                    || str_starts_with($current, 'resources/');
            }
        };

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->pluginPath, FilesystemIterator::SKIP_DOTS));
        $filteredIterator = new PluginFileIterator($iterator, $this->pluginPath, $filter);

        $count = count($phar->buildFromIterator($filteredIterator, $this->pluginPath));

        echo 'Added ' . $count . ' files to Phar' . PHP_EOL;

        $phar->compressFiles(Phar::GZ);
        $phar->stopBuffering();

        echo 'Phar created in ' . round(microtime(true) - $start, 3) . ' seconds' . PHP_EOL;
        echo 'Phar size: ' . round(filesize($pharOutput) / 1024, 2) . ' KB' . PHP_EOL;
        exit('Phar created successfully!' . PHP_EOL);
    }
}
