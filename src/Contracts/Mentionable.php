<?php

namespace Asmit\Mention\Contracts;

interface Mentionable
{
    /**
     * this must include key,id,nam
     * * @return array<array{id: int|string, key: string, image:string|null, link: string|null}>
     */
    public function getMentionableItems(?string $searchKey):array;
}
