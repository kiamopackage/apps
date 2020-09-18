# Apps bundle for Kiamo

## Installation

#### Step 1 - Download kiamopackage/apps with composer
```cmd
composer require kiamopackage/apps 
```

#### Step 2 - Load bundles to kernel
```php
<?php

// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
        new Symfony\Bundle\MonologBundle\MonologBundle(),
        new KiamoPackage\AppsBundle\KiamoPackageAppsBundle(),
    );
} 
```

#### Step 3 - Create entity for Kiamo Apps configuration storage
Create an Doctrine entity who extend KiamoPackage\AppsBundle\Entity\Config
```php
<?php

// src/Kiamo/Entity/KiamoApps/Config.php

namespace Kiamo\Entity\KiamoApps;

use Doctrine\ORM\Mapping as ORM;
use KiamoPackage\AppsBundle\Entity\Config as KiamoAppsConfig;

/**
 * @ORM\Entity()
 * @ORM\Table(name="kapps_config")
 */
class Config extends KiamoAppsConfig
{

}
```

#### Step 4 - Add table to database
Add the table corresponding to your new entity in your database
```mysql
CREATE TABLE `kapps_config` (
    `Id` INT UNSIGNED NOT NULL DEFAULT 1,
    `Active` TINYINT UNSIGNED DEFAULT 0,
    `ClientId` VARCHAR(255) NULL DEFAULT NULL,
    `ClientSecret` VARCHAR(255) NULL DEFAULT NULL,
    `Token` VARCHAR(255) NULL DEFAULT NULL,
    `ExpirationDate` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`Id`)
) ENGINE = MyISAM;
```

#### Step 5 - Add Kiamo Apps bundle configuration
* **entity_class:** _string_ **Required** Your extended class from KiamoPackage\AppsBundle\Entity\Config
* **host:** _string_ Domain of Kiamo Apps APIs with protocol part (default: https://kls.kiamo.fr)
* **port:** _interger_ Kiamo Apps APIs connection port (default: 443)
* **timeout:** _interger_ Default socket timeout in seconds (default: 15)

**< YML >**
```yaml
# app/config/admin/config.yml
kiamo_apps:
  entity_class: 'Kiamo\Entity\KiamoApps\Config'
  host: 'https://kls.kiamo.fr'
  port: 443
  timeout: 15
```
**< PHP >**
```php
<?php

// app/config/admin/config.php

$container->loadFromExtension(
    'kiamo_package_apps',
    [
        'entity_class' => \Kiamo\Entity\KiamoApps\Config::class,
        'host' => 'https://kls.kiamo.fr',
        'port' => 443,
        'timeout' => 15
    ]
);
```

#### Step 6 - Active bundle logs
The Apps bundle for Kiamo natively sends logs to the monolog "kiamoapps" channel, when the latter is activated in your project.

2 conditions to active logs:
* have symfony/monolog-bundle load on your Symfony app
    * symfony/monolog-bundle must be include in your composer dependencies
    * you must activate monolog bundle on your Kernel
* declare a handler on your app config for monolog:

**< YML >**
```yaml
# app/config/admin/config.yml
monolog:
    handlers:
        kiamoapps:
            channels: [kiamoapps]
            type: rotating_file
            ... 
```
**< PHP >**
```php
<?php

// app/config/admin/config.php

$container->loadFromExtension(
    'monolog',
    [
        'handlers' => [
            'kiamoapps' => [
                'channels' => ['kiamoapps'],
                'type' => 'rotating_file',
                ...
            ],
        ],
    ]
);
```
