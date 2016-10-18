[![Build Status](https://travis-ci.org/iulyanp/maintenance-bundle.svg?branch=master)](https://travis-ci.org/iulyanp/maintenance-bundle)
[![StyleCI](https://styleci.io/repos/71278770/shield?branch=master)](https://styleci.io/repos/71278770)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/iulyanp/maintenance-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/iulyanp/maintenance-bundle/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/iulyanp/maintenance-bundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/iulyanp/maintenance-bundle/build-status/master)

MaintenanceBundle
-----------------

The `MaintenanceBundle` is a simple bundle that helps you to set your application in maintenance.

### Installation

#### Step 1: Require the bundle with composer

Open your terminal and run one of the following commands to download the bundle into your vendor directory.

- If you have composer installed globally you can run:

```
$ composer require iulyanp/maintenance-bundle
```
- Else you can go with:

```
$ php composer.phar require iulyanp/maintenance-bundle
```

#### Step 2: Register the bundle in your AppKernel class

Register the bundle in the app/AppKernel.php file of your project:

```
<?php
/** app/AppKernel.php */

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(

            new Iulyanp\MaintenanceBundle\IulyanpMaintenanceBundle(),
        );
    }
}
```

#### Step 3: Configure the bundle

In order for the bundle to know when to set your website in maintenance you should configure it.

```
iulyanp_maintenance:
    enabled:   false
    due_date:  '14-10-2017 00:00:00'
    layout:
        signature: 'iulyanp'
        title: We are in maintenance
        description: Comming back soon...
```

You should use **enabled** and **due_date** configurations to chose when you want your application to be in maintenance and until what date.
You activate the maintenance by changing the enabled config to true but your website will be in maintenance only if you set the due date configuration.

The layout parameters are for changing the default title, message and signature from the default layout.
You can translate these configurations in the translation files.