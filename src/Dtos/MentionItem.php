<?php

namespace Asmit\FilamentMention\Dtos;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
readonly class MentionItem implements Arrayable
{
    public function __construct(
        public int $id,
        public string $username,
        public string $displayName,
        public string $avatar,
        public string $url,
    ) {
        //
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->setMentionItem();
    }

    /**
     * @return array<string, mixed>
     */
    private function setMentionItem(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'display_name' => $this->displayName,
            'avatar' => $this->avatar,
            'url' => $this->url,
        ];
    }
}
