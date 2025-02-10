<?php

namespace Asmit\Mention\Forms\Components;

use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class RichMention extends RichEditor
{
    protected string $view = 'asmit-mention::forms.components.rich-mention';

    protected array|\Closure $mentionsItems = [];

    protected Builder | \Closure $query;

    protected string $modelClass;

    protected string $triggerWith = '@';

    protected ?string $pluck = null;

    private string $pattern;

    public function modelClass(string $modelClass): static
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    public function mentionsItems(array|\Closure $mentionsItems): static
    {
        $this->mentionsItems = $mentionsItems;

        return $this;
    }


    public function dehydrateState(array &$state, bool $isDehydrated = true): void
    {
        $rawState = $state['data'][$this->getName()];
        if(!blank($this->getPluck())) {
            $state['data']['mentions'] = $this->extractMentions($rawState);
        }
        $state['data'][$this->getName()] = $this->removeIdFromText($rawState);
    }

    private function removeIdFromText(?string $text): string
    {
        $this->pattern ='/\(--([a-zA-Z0-9_]+)--\)/';
        return preg_replace($this->pattern, '', $text);
    }

    // Function to extract all @mentions
    protected function extractMentions(?string $text): array
    {
        $this->pattern ='/\(--([a-zA-Z0-9_]+)--\)/';
        preg_match_all($this->pattern, $text, $matches);

        // Return array of mentions
        // $matches[1] contains all @usernames
        return array_unique($matches[1]);
    }

    public function getMentionableItems(string $input = ''): array
    {
        if ($this->mentionsItems instanceof \Closure) {
            return ($this->mentionsItems)($input);
        }

        return $this->mentionsItems;
    }

    public function triggerWith(): string
    {
        return $this->triggerWith;
    }

    /**
     * @param string|\Closure $key
     * @return $this
     * The key must be included in the mentionsItems array
     */
    public function pluck(string|\Closure $key): static
    {
        $this->pluck = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getPluck(): ?string
    {
        return $this->evaluate($this->pluck);
    }
}
