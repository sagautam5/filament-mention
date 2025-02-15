<?php

namespace Asmit\Mention\Forms\Components;

use Filament\Forms\Components\RichEditor;

class RichMention extends RichEditor
{
    protected string $view = 'asmit-mention::forms.components.rich-mention';

    /**
     * @var array<string, mixed>|\Closure
     */
    protected array|\Closure $mentionsItems = [];

    protected string $modelClass;

    protected string $triggerWith = '@';

    protected string|null|\Closure $pluck = null;

    protected ?string $avatar = null;

    private string $pattern = '/\(--([a-zA-Z0-9_\.]+)--\)/';

    protected ?int $menuShowMinLength = null;

    protected ?string $lookupKey = null;

    protected ?string $displayName = null;

    protected ?int $menuItemLimit = null;

    /**
     * @param  array<string, mixed>|\Closure  $mentionsItems
     * @return $this
     */
    public function mentionsItems(array|\Closure $mentionsItems): static
    {
        $this->mentionsItems = $mentionsItems;

        return $this;
    }

    public function dehydrateState(array &$state, bool $isDehydrated = true): void
    {
        $rawState = $state['data'][$this->getName()];
        if (! blank($this->getPluck())) {
            $state['data']['mentions.'.$this->getName()] = $this->extractMentions($rawState);
        }
        $state['data'][$this->getName()] = $this->removeIdFromText($rawState);
    }

    private function removeIdFromText(?string $text): ?string
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

    /**
     * @return array<string|int, mixed>
     */
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
     * @return $this
     *               The key must be included in the mentionsItems array
     */
    public function pluck(string|\Closure $key): static
    {
        $this->pluck = $key;

        return $this;
    }

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
        return $this->evaluate($this->avatar) ?? config('mention.default.avatar');
    }

    public function menuShowMinLength(int $length = 2): self
    {
        $this->menuShowMinLength = $length;

        return $this;
    }

    public function getMenuShowMinLength(): int
    {
        return $this->menuShowMinLength ?? config('mention.default.menu_show_min_length');
    }

    public function lookupKey(string $key): self
    {
        $this->lookupKey = $key;

        return $this;
    }

    public function getLookupKey(): ?string
    {
        return $this->lookupKey ?? config('mention.default.lookup_key');
    }

    public function menuItemLimit(int $limit): self
    {
        $this->menuItemLimit = $limit;

        return $this;
    }

    public function getMenuItemLimit(): ?int
    {
        return $this->menuItemLimit ?? config('mention.default.menu_item_limit');
    }

    public function displayName(string $name): self
    {
        $this->displayName = $name;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName ?? config('mention.default.display_name');
    }
}
