<?php

namespace Asmit\FilamentMention\Forms\Components;

use Asmit\FilamentMention\Concerns\HasRichMentions;
use Filament\Forms\Components\RichEditor;

class RichMentionEditor extends RichEditor
{
    use HasRichMentions;

    protected string $view = 'asmit-filament-mention::forms.components.rich-mention';

    protected function setUp(): void
    {
        $this->afterStateUpdated(
            function (string $state, callable $set, self $component) {

                $mentions = $this->extractMentions($state);
                $mentionKey = 'mentions_'.$this->getName();

                if ($this->getPluck()) {
                    $component->state([
                        'state' => $this->removeAppendedExtraTextFromState($state),
                        $mentionKey => $mentions,
                    ]);
                } else {
                    $component->state($this->removeAppendedExtraTextFromState($state));
                }
            }
        );

    }

    private function removeAppendedExtraTextFromState(?string $text): ?string
    {
        return preg_replace($this->pattern, '', $text ?? '');
    }

    /**
     * @return array<int, mixed>
     */
    private function extractMentions(?string $text): array
    {
        preg_match_all($this->pattern, $text ?? '', $matches);

        /**
         * Return array of mentions
         * $matches[1] contains all @usernames
         */
        return array_unique($matches[1]);
    }
}
