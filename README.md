# Laravel api versioning

Laravel package for api versioning.

Do `composer require tenantcloud/laravel-api-versioning` to install the package.

### Commands

Install dependencies: 
`docker run -it --rm -v $PWD:/app -w /app composer install`

Run tests:
`docker run -it --rm -v $PWD:/app -w /app php:8.1-cli vendor/bin/pest`

Run php-cs-fixer on self: 
`docker run -it --rm -v $PWD:/app -w /app composer cs-fix`


### Usage:
If no versions needed or endpoint is the same for any version use default Laravel route definition
```php
// Api-Version: 1.0 -> CacToolCampaignController
// Api-Version: latest -> CacToolCampaignController
Route::post('cac/campaigns', [CacToolCampaignController::class, 'create']);
```

If route has versions - create some version rule
```php
// Api-Version: 4.0 -> 404
// Api-Version: 3.0 -> CacToolCampaignTemplateController
// Api-Version: 1.0 -> CacToolCampaignTemplateController
Route::get('cac/campaigns/template', CacToolCampaignTemplateController::class)
->versioned('<=3.0');
```
If we made break change and want to provide new action for new version we should register two rules - one for old versions and new for new and future versions
```php
// Api-Version: 3.0 -> CacToolCampaignTemplateController
// Api-Version: 4.0 -> CacToolCampaignTemplateForNewVersionController
// Api-Version: latest -> CacToolCampaignTemplateForNewVersionController
Route::get('cac/campaigns/template', CacToolCampaignTemplateController::class)
->versioned('<=3.0')
->versioned('>=4.0', [CacToolCampaignTemplateForNewVersionController::class, 'index'])
```
In some case we can get multiple break changes for some endpoint
```php
// Api-Version: 3.0 -> CacToolCampaignTemplateController
// Api-Version: 4.0 -> error
// Api-Version: 5.0 -> CacToolCampaignVersion5Controller
// Api-Version: latest -> CacToolCampaignVersion5Controller
Route::get('cac/campaigns/template', CacToolCampaignTemplateController::class)
->versioned('<=3.0')
->versioned('>=5.0', [CacToolCampaignVersion5Controller::class, 'index'])
```

All resolves registered by `APIVersioningServiceProvider::class`

| Interface              | Default implementation        | Description                                                                                     |
|------------------------|-------------------------------|-------------------------------------------------------------------------------------------------|
| ControllerDispatcher   | VersionControllerDispatcher   | Router dispatcher                                                                               |
| RequestVersionParser   | RequestHeaderVersionParser    | Parse version from `Illuminate\Http\Request` object                                             |
| ConstraintChecker      | SemanticConstraintChecker     | Constrain checker from `TenantCloud\APIVersioning\Version\Version` interface and array of rules |
| VersionParser          | SemanticVersionParser         | Version parser                                                                                  |
| StringConstraintParser | BuiltInStringConstraintParser | Parser from sting                                                                               |
