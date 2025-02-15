<?php

namespace Asmit\Mention\Forms\Components;

class FetchRichMention extends RichMention
{
    protected string $view = 'asmit-mention::forms.components.fetch-rich-mention';

    public function getAvatar(): ?string
    {
        if(!method_exists($this->getLivewire(), 'getMentionableItems')) {
            throw new \Exception('You must implement Mentionable contract in your Livewire component' );
        }
        return $this->evaluate($this->avatar);
    }
}
