<?php

namespace Asmit\Mention\Traits;

use App\Models\User;
use Asmit\Mention\Helpers\Helper;

trait HasMentionable
{
    public function getMentionableItems(?string $searchKey): array
    {
        return resolve(config('mention.mentionable.model'))->query()
            ->whereLike(config('mention.mentionable.search_key'), "%$searchKey%")->get()->map(function ($mentionable) {
            return [
                'id' => $id = $mentionable->{config('mention.mentionable.column.id')},
                'name' => $mentionable->{config('mention.mentionable.column.display_name')},
                'username' => $mentionable->{config('mention.mentionable.column.username')},
                'avatar' => $mentionable->{config('mention.mentionable.column.avatar')},
                'url' => Helper::getResolvedUrl($id)
            ];
        })->toArray();
    }

}
