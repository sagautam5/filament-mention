# Mention
The Mention plugin allows you to easily mention users in your Filament application using the Filament RichText editor. 
It includes a feature to extract specific fields from the mentioned user.

## Features
- Mention users in the Filament RichText editor.
- Extract specific fields from the mentioned user.
- Static search and dynamic search.
- User avatar and URL support.
- Customizable user model and fields.
- Customizable mention trigger character.
- Customizable mention suggestion limit.
- Customizable mention suggestion call text limit.

## Requirements
- PHP 7.4 or higher
- Laravel 8.0 or higher
- Filament 3.2 or higher

## Installation
You can install the package via composer:

```bash
composer require asmit/mention
```

## Configuration
After installing the package, you need to publish the configuration file using:

```bash
php artisan vendor:publish --provider="Asmit\Mention\MentionServiceProvider" --tag="asmit-mention-config"
```
This will create a `mention.php` file in your `config` directory. You can customize the configuration according to your needs.
```php
<?php

return [
    'mentionable' => [
        'model' => \App\Models\User::class,
        'column' => [
            'id' => 'id',
            'display_name' => 'name',
            'username' => 'username',
            'avatar' => 'profile',
            'url' => 'admin/users/{id}',
        ],
        'lookup_key' => 'username', // this will be used on static search
        'search_key' => 'username', // this will be used on dynamic search
    ],
    'default' => [
        'trigger_with' => '@',
        'menu_show_min_length' => 2,
        'menu_item_limit' => 10,
    ],
];
```
>From the configuration file, you can customize the user model, fields, mention trigger character, mention suggestion limit, and mention suggestion call text limit.
There you can see lookup_key and search_key. lookup_key is used for static search and search_key is used for dynamic search.
The lookup_key must be key of your array and search_key must be column name of your table.

## Usage
Here in mentionable you can use the mention field with two different ways. One is static search and another is dynamic search.

### Static Search
In static search, you can use the lookup_key to search the mentionable. It will search the user from the array. For static search you can you ``RichMentionEditor`` field.
The ``RichMentionEditor`` fetch all the mentionable data first and then search the mention item from the fetched data.
```php
use Asmit\Mention\Forms\Components\FetchMentionEditor;

RichMentionEditor::make('bio')
    ->columnSpanFull(),
```
You can also change the data by pass the closure function ``mentionsItems`` in the ``RichMentionEditor`` field.
example:
```php
RichMentionEditor::make('bio')
  ->mentionsItems(function () {
      return User::all()->map(function ($user) {
          return [
              'username' => $user->username,
              'name' => $user->name,
              'avatar' => $user->profile,
              'url' => 'admin/users/' . $user->id,
          ];
      })->toArray();
  })
```

You can change the lookup_key with chaining the method ``lookupKey`` in the ``RichMentionEditor`` field.
```php
RichMentionEditor::make('bio')
  ->mentionsItems(function () {
      return User::all()->map(function ($user) {
          return [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'avatar' => $user->profile,
                'url' => 'admin/users/' . $user->id,
          ];
      })->toArray();
  })
    ->lookupKey('username')
```
> NOTE: The data should be mapped like the above example.

### Dynamic Search
In dynamic search, you can use the search_key to search the mentionable. It will search the mentionable from the database. For dynamic search you can you ``FetchMentionEditor`` field.
> NOTE: The search_key must be the column name of your table.

Before use the ``FetchMentionEditor`` field you need to implement the ``Mentionable`` interface in your livewire page. And then ```use Asmit\Mention\Traits\Mentionable;``` in your livewire page.
It will add the method ``getMentionableItems(string $searhKey)`` in your livewire page. You can use this method to fetch the mentionable data.

```php
use Asmit\Mention\Forms\Components\FetchMentionEditor;

FetchMentionEditor::make('Fetch')
    ->columnSpanFull(),
```
> You can override the method ``getMentionableItems`` in your livewire page to fetch the mentionable data.

