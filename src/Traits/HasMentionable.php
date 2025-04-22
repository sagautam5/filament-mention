<?php

namespace Asmit\FilamentMention\Traits;

use Asmit\FilamentMention\Dtos\MentionItemDto;
use Asmit\FilamentMention\Helpers\Helper;

/**
 * @phpstan-ignore-next-line
 */
trait HasMentionable
{
    public function getMentionableItems(?string $searchKey): array
    {
        dd(config('filament-mention.mentionable.search_column'));

        return resolve(config('filament-mention.mentionable.model'))->query()
            ->whereLike(config('filament-mention.mentionable.search_column'), "%$searchKey%")->get()->map(function ($mentionable) {
                return (new MentionItemDto(
                    id: $id = $mentionable->{config('filament-mention.mentionable.column.id')},
                    username: $mentionable->{config('filament-mention.mentionable.column.username')},
                    displayName: $mentionable->{config('filament-mention.mentionable.column.display_name')},
                    avatar: $mentionable->{config('filament-mention.mentionable.column.avatar')},
                    url: Helper::getResolvedUrl($id),
                ))->toArray();
            })->toArray();
    }
}
