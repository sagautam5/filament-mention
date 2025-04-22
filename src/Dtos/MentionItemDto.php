<?php

namespace Asmit\FilamentMention\Dtos;

use Illuminate\Contracts\Support\Arrayable;

readonly class MentionItemDto implements Arrayable
{
    public function __construct(
        public int $id,
        public string $userName,
        public string $displayName,
        public string $avatar,
        public string $url,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->userName,
            'display_name' => $this->displayName,
            'avatar' => $this->avatar,
            'url' => $this->url,
        ];
    }
}
