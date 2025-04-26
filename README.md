# Filament Mention
The **Mention** plugin allows you to easily mention users in your Filament application using the Filament RichText editor. It supports extracting specific fields from the mentioned user, such as their username, and id. The plugin offers both **static search** (preloaded data) and **dynamic search** (real-time database queries) for mentions.

![Filament Mention Plugin](https://raw.githubusercontent.com/AsmitNepali/filament-mention/refs/heads/main/images/cover.jpg)
---
<p class="flex items-center justify-center">
    <a href="https://packagist.org/packages/asmit/filament-mention">
        <img alt="Packagist" src="https://img.shields.io/packagist/v/asmit/filament-mention.svg?style=for-the-badge">
    </a>
    <a href="https://packagist.org/packages/asmit/filament-mention/stats">
        <img alt="Packagist" src="https://img.shields.io/packagist/dt/asmit/filament-mention.svg?style=for-the-badge">
    </a>
    <a href="#">
        <img alt="Packagist" src="https://img.shields.io/packagist/l/asmit/filament-mention.svg?style=for-the-badge">
    </a>
    <a href="https://packagist.org/packages/asmitnepali/filament-mention">
        <img alt="Packagist" src="https://img.shields.io/github/stars/asmitnepali/filament-mention?style=for-the-badge">
    </a>
    <a href="https://github.com/AsmitNepali/filament-mention/forks">
        <img alt="Packagist" src="https://img.shields.io/github/forks/asmitnepali/filament-mention?style=for-the-badge">
    </a>
</p>

---
## Features
- **Mention users** in the Filament RichText editor.
- **Extract specific fields** from the mentioned user (e.g. username, id).
- **Static search**: Preload and search from a dataset.
- **Dynamic search**: Fetch data from the database in real-time.
- **Customizable user model and fields**: Use your own `User` model and define which fields to display.
- **Customizable mention trigger character**: Change the default `@` trigger to any character.
- **Customizable suggestion limits**: Control the number of suggestions displayed and the minimum text length to trigger the search.
- **Avatar and URL support**: Display user avatars and link to their profiles.

## Requirements
- Filament 3.2 or higher

## Installation

1. Install the package via Composer:
   ```bash
   composer require asmit/filament-mention
    ```
2. After installation and update please run the following command to publish the assets:
   ```bash
   php artisan filament:assets
   ```
3. Publish the configuration file:
   ```bash
   php artisan vendor:publish --provider="Asmit\FilamentMention\FilamentMentionServiceProvider" --tag="asmit-filament-mention-config"
   ```
This will create a `filament-mention.php` file in your `config` directory. You can customize the configuration according to your needs.

---

## Upgrading from 1.x to 2.x
If you are upgrading from version 1.x to 2.x, please note these changes: 
1. The configuration key `search_key` is now `search_column`.
2. The `HasMentionable` trait is now `HasMentionableForm`.
3. The `mentionsItems` method is now `mentionableItems`.
4. The form data structure after pluck is change.
   ```php
    [
        'note' => [
            'state' => 'your comment message'
            'mentioned_YOUR-PLUCK-KEY' => [
                0 => 1,
                1 => 2,
                2 => 3,
            ]
    ]```
5. The `FetchMentionEditor` is deprecated. You can use dynamic search with `getMentionableItemsUsing` method in the `RichMentionEditor` field.

## Configuration
The configuration file (``config/filament-mention.php``) allows you to customize the plugin behavior. Here’s an example configuration:

```php
return [
    'mentionable' => [
        'model' => \App\Models\User::class, // The model to use for mentions
        'column' => [
            'id' => 'id', // Unique identifier for the user
            'display_name' => 'name', // Display name for the mention
            'username' => 'username', // Username for the mention
            'avatar' => 'profile', // Avatar field (e.g. profile picture URL)
        ],
        'url' => 'admin/users/{id}', // this will be used to generate the url for the mention item
        'lookup_key' => 'username', // Used for static search (key in the dataset)
        'search_column' => 'username', // this will be used on dynamic search 
    'default' => [
        'trigger_with' => '@', // Character to trigger mentions (e.g. @)
        'menu_show_min_length' => 2, // Minimum characters to type before showing suggestions
        'menu_item_limit' => 10, // Maximum number of suggestions to display
    ],
];
```
---

### Key Configuration Options:
 - ``mentionable.model``: The Eloquent model to use for mentions (e.g. User).
 - ``mentionable.column``: Map the fields to use for mentions (e.g. id, name, etc.).
 - ``mentionable.url``: URL pattern for the mention item (e.g. admin/users/{id}).
 - ``mentionable.lookup_key``: Used for static search (key in the dataset).
 - ``mentionable.search_column``: Used for dynamic search (database column).
 - ``default.trigger_with``: Character to trigger mentions (e.g. @).
 - ``default.menu_show_min_length``: Minimum characters to type before showing suggestions.
 - ``default.menu_item_limit``: Maximum number of suggestions to display.

### Recommendations:
- **Use cache to store the mentionable data for static search.**
- **Add indexes to the columns used for dynamic search.**
- **If you are use mention editor inside the modal, please disable grammarly extension by ``disableGrammarly()``.**

---
## Usage
### 1. Static Search
Static search preloads all mentionable data and searches within that dataset. For static search you can you ``RichMentionEditor`` field.

The ``RichMentionEditor`` fetch all the mentionable data first and then search the mention item from the fetched data.

```php
use Asmit\Mention\Forms\Components\RichMentionEditor;

RichMentionEditor::make('bio')
    ->columnSpanFull(),
```
You can also change the data by pass the closure function ``mentionableItems`` in the ``RichMentionEditor`` field.

example:
```php
use Asmit\FilamentMention\Forms\Components\RichMentionEditor;
use Asmit\FilamentMention\Dtos\MentionItem;

RichMentionEditor::make('comments')
    ->key(fn () => rand())
    ->disableGrammarly()
    ->lookupKey('username')
    ->mentionableItems(function () {
        return User::all()->map(function ($user) {
            return (new MentionItem(
                id: $user->id,
                username: $user->username,
                displayName: $user->name,
                avatar: $user->profile,
                url: '/users/admin'.$user->id,
            ));
        })->toArray();
    }),
```

#### Key Points
 - The ``mentionableItems`` method should return an array of mentionable items.
 - The most convenient way to use the ``mentionableItems`` method is to use the ``MentionItem`` DTO.
 - Use the ``lookup_key`` to search the mentionable item.
You can change the ``lookup_key`` with chaining the method ``lookupKey`` in the ``RichMentionEditor`` field.

> NOTE: If you not use ``mentionableItems`` then it will use the configuration from config file.

### 2. Dynamic Search
To enable this feature you need to use the ``HasMentionableForm`` in to your livewire page.

```php
use \Asmit\FilamentMention\Concerns\HasMentionableForm

class FilamentPage {
    use HasMentionableForm;
    // ...
}
```

Next, you can search the mentionable data from the database using the ``getMentionableItemsUsing`` method in the ``RichMentionEditor`` field.
```php
use Asmit\FilamentMention\Forms\Components\RichMentionEditor;
use Asmit\FilamentMention\Dtos\MentionItem;

RichMentionEditor::make('comments')
    ->key(fn () => rand())
    ->lookupKey('username')
    ->disableGrammarly()
    ->placeholder('Write your comment here...')
    ->getMentionableItemsUsing(function ($query) {
        return User::search($query)
            ->get()
            ->map(function ($user) {
                return new MentionItem(
                    id: $user->id,
                    username: $user->username,
                    displayName: $user->name,
                    avatar: $user->profile,
                    url: '#');
            })->toArray();
    })

```

___

## Pluck
The plugin allows you to extract specific fields from the mentioned user. You can use the ``pluck`` method to extract the fields.
This feature helps you to customize the mention output according to your needs.

```php
use \Asmit\FilamentMention\Forms\Components\RichMentionEditor;

RichMentionEditor::make('note')
    ->pluck('id')
    ->getMentionableItemsUsing(function ($query) {
        return User::search($query)
                ->get()
                ->map(function ($user) {
                    return new MentionItem(
                        id: $user->id,
                        username: $user->username,
                        displayName: $user->name,
                        avatar: $user->profile,
                        url: '#');
                })->toArray();
        })
```
The ``pluck`` method accepts the ``key`` to extract the field from the mentioned user. The key should be ``id`` or ``username``.
It extracts the data from the MentionItem and return the data in the array format.

It will add the new data attribute named ``mentioned_[YOUR PLUCK LEY]``.
If you inspect the data it will return like
```php
    [
        'note' => [
            'state' => 'your comment message'
            'mentioned_id' => [
                0 => 1,
                1 => 2,
                2 => 3,
            ]
    ]
```

---

## Credits
- [Asmit Nepal][link-asmit]
- [Kishan Sunar][link-kishan]
- Jordan Humphreys (Creator of [TributeJs][link-tributejs])

### Security

If you discover a security vulnerability within this package, please send an e-mail to asmitnepali99@gmail.com, All security vulnerabilities will be promptly addressed.

### 🤝 Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### 📄 License
The MIT License (MIT). Please see [License File](LICENSE.txt) for more information.


<i>Made with love by Asmit Nepali</i>


[ico-version]: https://img.shields.io/packagist/v/asmit/filament-mention.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/asmit/filament-mention.svg?style=flat-square
[ico-stable]: https://img.shields.io/packagist/s/asmit/filament-mention.svg?style=flat-square
[ico-license]: https://img.shields.io/packagist/l/asmit/filament-mention.svg?style=flat-square
[ico-forks]: https://img.shields.io/github/forks/asmitnepali/filament-mention?style=flat-square
[ico-stars]: https://img.shields.io/github/stars/asmitnepali/filament-mention?style=flat-square


[link-asmit]: https://github.com/AsmitNepali
[link-kishan]: https://github.com/Ksunar
[link-tributejs]:https://github.com/zurb/tribute
[link-packagist]: https://packagist.org/packages/asmit/filament-mention
[link-downloads]: https://packagist.org/packages/asmit/filament-mention
