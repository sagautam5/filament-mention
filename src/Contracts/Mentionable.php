<?php

namespace Asmit\FilamentMention\Contracts;

interface Mentionable
{
    /**
     * This must include name, username, image, url
     *
     * @return array<array<string, mixed>>
     */
    public function getMentionableItems(?string $searchKey): array;
}
