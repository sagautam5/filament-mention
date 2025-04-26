<?php

namespace Asmit\FilamentMention\Forms\Components;

/**
 * @deprecated Use RichMentionEditor instead with getMentionableItemsUsing().
 */
class FetchMentionEditor extends RichMentionEditor
{
    protected string $view = 'asmit-filament-mention::forms.components.fetch-rich-mention';
}
