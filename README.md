# Kassoulet360LBundle

## Installation

Perform the following steps to install and use the basic functionality

* Download Kassoulet360LBundle using Composer
* Enable the bundle
* Configure the bundle

### Step 1: Download the Kassoulet360LBundle

Add Kassoulet360LBundle to your composer.json using the following construct:

    $ composer require kassoulet/360lBundle

Composer will install the bundle to your project's ``vendor/kassoulet/360lbundle`` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Kassoulet\360LBundle\Kassoulet360LBundle(),
    );
}
```

### Step 3: Configure the bundle

Add your credentials in config file : company and apiKey

```yaml
# app/config/config.yml

kassoulet_360L:
    company_id: yourCompanyID
    api_key: yourAPIKey            
```