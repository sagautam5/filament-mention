<?php

namespace Asmit\FilamentMention\Concerns;

use Asmit\FilamentMention\Dtos\MentionItem;
use Asmit\FilamentMention\Helpers\Helper;
use Closure;
use Illuminate\Contracts\Support\Arrayable;

trait HasRichMentions
{
    /**
     * @var array<string, mixed>|Closure
     */
    protected array|Closure $mentionableItems = [];

    protected ?Closure $getMentionableItemsUsing = null;

    protected string $modelClass;

    protected string $triggerWith = '@';

    protected string|null|Closure $pluck = null;

    protected ?string $avatar = null;

    private string $pattern = '/\(--([a-zA-Z0-9_\.]+)--\)/';

    protected ?int $menuShowMinLength = null;

    protected ?string $lookupKey = null;

    protected bool $displayName = true;

    protected ?int $menuItemLimit = null;

    public function getMentionableItemsUsing(Closure $callback): static
    {
        $this->getMentionableItemsUsing = $callback;

        return $this;
    }

    /**
     * @param  array<string, mixed>|Closure  $mentionsItems
     * @return $this
     *
     * @deprecated Use mentionableItems instead
     */
    public function mentionsItems(array|Closure $mentionsItems): static
    {
        $this->mentionableItems($mentionsItems);

        return $this;
    }

    /**
     * @param  array<string, mixed>|Closure  $mentionsItems
     * @return $this
     */
    public function mentionableItems(array|Closure $mentionsItems): static
    {
        $this->mentionableItems = $mentionsItems;

        return $this;
    }

    /**
     * @return array<string|int, mixed>
     */
    public function getMentionableItems(): array
    {
        $this->mentionableItems = $this->evaluate($this->mentionableItems);

        if (blank($this->mentionableItems)) {
            $this->mentionableItems = $this->getMentionItemsUsingConfig();
        }

        /** @phpstan-ignore-next-line  */
        return collect($this->mentionableItems)
            ->map(fn ($item) => $item instanceof MentionItem ? $item->toArray() : $item)
            ->toArray();
    }

    public function triggerWith(): string
    {
        return $this->triggerWith;
    }

    /**
     * @return $this
     *               The key must be included in the mentionsItems array
     */
    public function pluck(string|Closure $key): static
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
        return $this->evaluate($this->avatar) ?? config('filament-mention.default.avatar');
    }

    public function menuShowMinLength(int $length = 2): self
    {
        $this->menuShowMinLength = $length;

        return $this;
    }

    public function getMenuShowMinLength(): int
    {
        return $this->menuShowMinLength ?? config('filament-mention.default.menu_show_min_length');
    }

    public function lookupKey(string $key): self
    {
        $this->lookupKey = $key;

        return $this;
    }

    public function getLookupKey(): ?string
    {
        return $this->lookupKey ?? config('filament-mention.mentionable.lookup_key');
    }

    public function menuItemLimit(int $limit): self
    {
        $this->menuItemLimit = $limit;

        return $this;
    }

    public function getMenuItemLimit(): ?int
    {
        return $this->menuItemLimit ?? config('filament-mention.default.menu_item_limit');
    }

    /**
     * @return array<int, mixed>
     */
    public function getSearchResults(string $search): array
    {
        if (! $this->getMentionableItemsUsing) {
            return [];
        }

        $this->mentionableItems = $this->evaluate($this->getMentionableItemsUsing, [
            'query' => $search,
        ]);

        if ($this->mentionableItems instanceof Arrayable) {
            return $this->mentionableItems->toArray();
        }

        return $this->mentionableItems;
    }

    /**
     * @return array<string, mixed>
     */
    private function getMentionItemsUsingConfig(): array
    {
        return resolve(config('filament-mention.mentionable.model'))
            ->query()->get()->map(function ($item) {
                return (new MentionItem(
                    id: $item->{config('filament-mention.mentionable.column.id')},
                    username: $item->{config('filament-mention.mentionable.column.username')},
                    displayName: $item->{config('filament-mention.mentionable.column.display_name')},
                    avatar: $item->{config('filament-mention.mentionable.column.avatar')},
                    url: Helper::getResolvedUrl($item->{config('filament-mention.mentionable.column.id')}),
                ))->toArray();
            })->toArray();
    }

    public function getEnableDynamicSearch(): bool
    {
        return ! is_null($this->getMentionableItemsUsing);
    }
}
