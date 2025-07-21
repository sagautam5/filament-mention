@use('Filament\Support\Facades\FilamentAsset')
@use('Filament\Support\Facades\FilamentView')
@php
    $statePath = $getStatePath();
    $staticMentionableItems = $getMentionableItems();
    $triggerWith = $triggerWith();
    $pluck = $getPluck();
    $menuShowMinLength = $getMenuShowMinLength();
    $menuItemLimit = $getMenuItemLimit();
    $lookupKey = $getLookupKey();
    $enableDynamicSearch = $getEnableDynamicSearch();
    $triggerConfigs = $getTriggerConfigs();
    $prefix = $getPrefix();
    $suffix = $getSuffix();
    $label = $getLabelKey();
    $hint = $getHintKey();
//    dd($staticMentionableItems);
@endphp

<div id="add-list-{{ $getId() }}"
     wire:key="{{ $getId() }}"
     wire:ignore
     x-ignore
    @if (FilamentView::hasSpaMode(url()->current()))
        ax-load="visible"
    @else
            ax-load
     @endif
    ax-load-src="{{ FilamentAsset::getAlpineComponentSrc(id: 'asmit-filament-mention', package: 'asmit/filament-mention') }}"
    x-load-css="[@js(FilamentAsset::getStyleHref(id: 'asmit-filament-mention', package: 'asmit/filament-mention'))]"
     x-data="editor()">
    @include('filament-forms::components.rich-editor')
</div>
