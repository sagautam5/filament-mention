<?php

namespace Asmit\FilamentMention\Concerns;

use Asmit\FilamentMention\Forms\Components\RichMentionEditor;
use Livewire\Attributes\Renderless;

/**
 * @phpstan-ignore-next-line
 */
trait HasMentionableForm
{
    #[Renderless]
    public function getMentionsItems(string $search, string $statePath): array
    {
        foreach ($this->getCachedForms() as $form) {
            if ($results = $this->getFilamentRichMentionResults($form, $statePath, $search)) {
                return $results;
            }
        }

        return [];
    }

    public function getFilamentRichMentionResults($form, string $statePath, string $search): array
    {
        foreach ($form->getComponents() as $component) {
            if ($component instanceof RichMentionEditor && $component->getStatePath() === $statePath) {
                return $component->getSearchResults($search);
            }

            foreach ($component->getChildComponentContainers() as $container) {
                if ($container->isHidden()) {
                    continue;
                }

                if ($results = $container->getSelectSearchResults($statePath, $search)) {
                    return $results;
                }
            }
        }

        return [];
    }
}
