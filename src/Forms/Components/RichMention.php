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

    private string $pattern = '/\(--([a-zA-Z0-9_\.]+)--\)/';

    protected ?int $menuShowMinLength = null;

    protected ?string $lookupKey = null;

    protected ?string $displayName = null;

    protected ?int $menuItemLimit = null;

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
        return preg_replace($this->pattern, '', $text);
    }

    // Function to extract all @mentions
    private function extractMentions(?string $text): array
    {
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
        $avatar = $this->evaluate($this->avatar) ?? config('mention.default.avatar');
       if(!array_key_exists($avatar, $this->getMentionableItems()[0]) && !blank($avatar)) {
           throw new \Exception("$avatar key not found in mentionsItems array");
        }
        return $avatar;
    }

    public function menuShowMinLength(int $length = 2):self
    {
        $this->menuShowMinLength = $length;
        return $this;
    }

    public function getMenuShowMinLength():int
    {
        return $this->menuShowMinLength ?? config('mention.default.menu_show_min_length');
    }

    public function lookupKey(string $key):self
    {
        $this->lookupKey = $key;
        return $this;
    }

    public function getLookupKey():?string
    {
        return $this->lookupKey ?? config('mention.default.lookup_key');
    }

    public function menuItemLimit(int $limit):self
    {
        $this->menuItemLimit = $limit;
        return $this;
    }

    public function getMenuItemLimit():int
    {
        return $this->menuItemLimit ?? config('mention.default.menu_item_limit');
    }

    public function displayName(string $name):self
    {
        $this->displayName = $name;
        return $this;
    }
    public function getDisplayName(): ?string
    {
        return $this->displayName ?? config('mention.default.display_name');
    }
}

