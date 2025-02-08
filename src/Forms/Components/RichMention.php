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

    public function modelClass(string $modelClass): static
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    public function getModelClass(): string
    {
        return $this->evaluate($this->modelClass);
    }

    public function modifyingQuery(Builder | \Closure $query)
    {
        $this->query = $query;

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
        $state['data']['mentions'] = $this->extractMentions($rawState);
        $state['data'][$this->getName()] = $this->removeIdFromText($rawState);
    }

    private function removeIdFromText(?string $text)
    {
        $pattern = '/\(--(\d+)--\)/';
        return preg_replace($pattern, '', $text);
    }

    // Function to extract all @mentions
    protected function extractMentions(?string $text): array
    {
        $pattern = '/\(--(\d+)--\)/';
        preg_match_all($pattern, $text, $matches);
        // Return array of mentions
        return array_unique($matches[1]);  // $matches[1] contains all @usernames
    }

    public function getMentionsItems(string $input = ''): array
    {

        Log::info('Mentions Items: ' . $input);
        if ($this->mentionsItems instanceof \Closure) {
            return ($this->mentionsItems)($input);
        }

        return $this->mentionsItems;
    }

//    public function getQuery()
//    {
//        if($this->query instanceof \Closure) {
//            return ($this->query)();
//        }
//        return $this->query;
//    }
}
