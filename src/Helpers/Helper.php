<?php

namespace Asmit\FilamentMention\Helpers;

final class Helper
{
    public static function getResolvedUrl(string|int $key): string
    {
        if (blank(config('filament-mention.mentionable.url'))) {
            return '#';
        }

        return url(str_replace('{id}', $key, config('filament-mention.mentionable.url')));


    }
}
