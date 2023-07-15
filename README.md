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
