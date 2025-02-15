<?php

namespace Asmit\FilamentMention\Helpers;

final class Helper
{
    public static function getResolvedUrl(string|int $key): string
    {
        if (blank(config('mention.mentionable.model.url'))) {
            return '#';
        }

        return url(str_replace('{id}', $key, config('mention-editor.mentionable.model.url')));

    }
}
