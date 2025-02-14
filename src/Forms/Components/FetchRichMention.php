<?php

namespace Asmit\Mention\Forms\Components;

class FetchRichMention extends RichMention
{
    protected string $view = 'asmit-mention::forms.components.fetch-rich-mention';

    public function getAvatar(): ?string
    {
        return $this->evaluate($this->avatar);
    }
}
