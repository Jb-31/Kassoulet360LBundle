# KassouletThreesixtyLearningBundle

## Installation

Perform the following steps to install and use the basic functionality

* Download KassouletThreesixtyLearningBundle using Composer
* Enable the bundle
* Configure the bundle

### Step 1: Download the KassouletThreesixtyLearningBundle

Add KassouletThreesixtyLearningBundle to your composer.json :

    "kassoulet/threesixtyLearningBundle": "dev-master"

Composer will install the bundle to your project's ``vendor/kassoulet/threesixtylearningbundle`` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Kassoulet\ThreesixtyLearningBundle\KassouletThreesixtyLearningBundle(),
    );
}
```

### Step 3: Configure the bundle

Add your credentials in config file : CompanyID and ApiKey

```yaml
# app/config/config.yml

kassoulet_threesixty_learning:
    company_id: yourCompanyID
    api_key: yourAPIKey             
``` 