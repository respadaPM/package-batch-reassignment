# Processmaker Package Batch Reassignment
This package provides the necessary base code to start the developing a package in ProcessMaker 4.

## Development
If you need to create a new ProcessMaker package run the following commands:

```
git clone https://github.com/ProcessMaker/package-batch-reassignment.git
cd package-batch-reassignment
php rename-project.php package-batch-reassignment
composer install
npm install
npm run dev
```

## Installation
* Use `composer require processmaker/package-batch-reassignment` to install the package.
* Use `php artisan package-batch-reassignment:install` to install generate the dependencies.

## Navigation and testing
* Navigate to requests tab in your ProcessMaker 4
* Select `Package Batch Reassignment` from the left sidebar

## Uninstall
* Use `php artisan package-batch-reassignment:uninstall` to uninstall the package
* Use `composer remove processmaker/package-batch-reassignment` to remove the package completely
