<div align="center">

# Laravel Filament Slug

[![Novius CI](https://github.com/novius/laravel-filament-slug/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/novius/laravel-filament-slug/actions/workflows/main.yml)
[![Packagist Release](https://img.shields.io/packagist/v/novius/laravel-filament-slug.svg?maxAge=1800&style=flat-square)](https://packagist.org/packages/novius/laravel-filament-slug)
[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](http://www.gnu.org/licenses/agpl-3.0)

</div>

## Introduction

This package add a Slug field to Filament Forms

## Requirements

* PHP >= 8.2
* Laravel >= 11.0
* Laravel Filament >= 4

## Installation

```sh
composer require novius/laravel-filament-slug
```

## Usage

```php
class YourResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                $title = TextInput::make('title')
                    ->required(),
    
                Slug::make('slug')
                    // First parameter of fromField() must be the TextInput instance from which the slug is generated.
                    // Second parameter is optional. If passed, must be a closure returning if the slug generation should be skip or not.
                    ->fromField($title, fn (Get $get) => ! $get('other_value'))
                    // Slug inherit from TextInput. You can use all other method of TextInput. 
                    ->required()
                    ->string()
                    ->regex('/^(\/|[a-zA-Z0-9-_]+)$/')
                    ->unique(
                        YourModel::class,
                        'slug',
                        ignoreRecord: true
                    ),
            ]);
    }
}
``` 

## Lint

Run php-cs with:

```sh
composer run-script lint
```

## Contributing

Contributions are welcome!

Leave an issue on GitHub, or create a Pull Request.

## Licence

This package is under [GNU Affero General Public License v3](http://www.gnu.org/licenses/agpl-3.0.html) or (at your option) any later version.
