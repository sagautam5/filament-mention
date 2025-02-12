<?php

namespace Asmit\Mention\Forms\Components;

use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;

class RichMention extends RichEditor
{
    protected string $view = 'asmit-mention::forms.components.rich-mention';

    protected array|\Closure $mentionsItems = [];

    protected Builder|\Closure $query;

    protected string $modelClass;

    protected string $triggerWith = '@';

    protected ?string $pluck = null;

    protected ?string $avatar = null;

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
        if (!blank($this->getPluck())) {
            $state['data']['mentions.'.$this->getName()] = $this->extractMentions($rawState);
        }
        $state['data'][$this->getName()] = $this->removeIdFromText($rawState);
    }

    private function removeIdFromText(?string $text): string
    {
        $this->pattern = '/\(--([a-zA-Z0-9_]+)--\)/';
        return preg_replace($this->pattern, '', $text);
    }

    // Function to extract all @mentions
    private function extractMentions(?string $text): array
    {
        $this->pattern = '/\(--([a-zA-Z0-9_]+)--\)/';
        preg_match_all($this->pattern, $text, $matches);

        /**
         * Return array of mentions
         * $matches[1] contains all @usernames
         */
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

    public function avatar(string $key): static
    {
        $this->avatar = $key;

        return $this;
    }


    /**
     * @throws \Exception
     */
    public function getAvatar(): ?string
    {
        $avatar = $this->evaluate($this->avatar);
       if(!array_key_exists($avatar, $this->getMentionableItems()[0]) && !blank($avatar)) {
           throw new \Exception("$avatar key not found in mentionsItems array");
        }
        return $avatar;
    }
}
