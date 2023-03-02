# Laravel api versioning

Laravel package for api versioning.

Installation
In your composer.json, add this repository:

```
"repositories": [
{
"type": "git",
"url": "https://github.com/tenantcloud/laravel-api-versioning"
}
],
```
Then do composer require tenantcloud/laravel-api-versioning to install the package.

### Commands

Install dependencies: 
`docker run -it --rm -v $PWD:/app -w /app composer install`

Run tests:
`docker run -it --rm -v $PWD:/app -w /app php:8.1-cli vendor/bin/pest`

Run php-cs-fixer on self: 
`docker run -it --rm -v $PWD:/app -w /app composer cs-fix`
