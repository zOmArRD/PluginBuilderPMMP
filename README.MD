
# PluginBuilder for PMMP

The ultimate API for compiling your PocketMineMP plugins


## Installation

Install Composer, and run this command in your project directory:

```sh
composer require zomarrd/pluginbuilderpmmp
```

## Usage/Examples

1- Create a ``build.php`` file in the root folder of your plugin and add the following code.

<br>

#### CODE EXAMPLE

```php
<?php declare(strict_types=1);

// Necesario si usas Composer.
require_once __DIR__ . '/../vendor/autoload.php';

// Importar la clase PluginBuilder.
use zOmArRD\Builder\PluginBuilder;

// Crear una instancia de PluginBuilder.
$pluginBuilder = new PluginBuilder(dirname(__DIR__));

// Compilar el plugin.
$pluginBuilder->build();
```


<br>

#### CONSOLE OUTPUT
```shell
Creating Phar file...
Added 2691 files to Phar
Phar created in 2.899 seconds
Phar size: 7082.07 KB
Phar created successfully!

Process finished with exit code 0
```
