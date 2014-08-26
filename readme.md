## Laravel 4 Scripts-dev

This packages execute all commands added into composer scripts-dev directive.

### Installation

Require this package in your composer.json and run composer update (or run `composer require menthol/scripts-dev:dev-master` directly):

    "menthol/scripts-dev": "dev-master"

After updating composer, add the ServiceProvider to the providers array in app/config/app.php

    'Menthol\ScriptsDev\ScriptsDevServiceProvider',

Publish the config.

    php artisan config:publish menthol/scripts-dev

Configure your local dev environment by adding `` 'dev' => true, `` to your local package config file.

You can configure your composer.json.
In each scripts step add `` php artisan scripts-dev {step} ``

    "scripts":{
        "post-update-cmd":[
            "php artisan clear-compiled",
            "php artisan optimize",
            "php artisan scripts-dev post-update-cmd"
        ]
    },

Now you can add your dev commands

    "scripts-dev":{
        "post-update-cmd":[
            "php artisan ide-helper:generate"
        ]
    },
