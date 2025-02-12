<?php

namespace Asmit\Mention\Forms\Components;

class FetchRichMention extends RichMention
{
    protected string $view = 'asmit-mention::forms.components.fetch-rich-mention';

    protected array|\Closure $mentionsItems = [];

    public function getAvatar(): ?string
    {
        $avatar = $this->evaluate($this->avatar);
       if(!array_key_exists($avatar, $this->getLivewire()->getMentionableItems(null)[0]) && !blank($avatar)) {
           throw new \Exception("$avatar key not found in mentionsItems array");
       }
        return $avatar;
    }
}
