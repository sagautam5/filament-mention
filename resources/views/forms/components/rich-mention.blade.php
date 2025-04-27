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
     x-data="mention({
        statePath: '{{ $statePath }}',
        mentionableItems: @js($staticMentionableItems),
        triggerWith: @js($triggerWith),
        pluck: @js($pluck),
        menuShowMinLength: @js($menuShowMinLength),
        menuItemLimit: @js($menuItemLimit),
        lookupKey: @js($lookupKey),
        loadingItemString: '{{ trans('asmit-filament-mention::translations.loading') }}',
        noResultsString: '{{ trans('asmit-filament-mention::translations.no_results') }}',
        triggerConfigs: @js($triggerConfigs),
        prefix: @js($prefix),
        suffix: @js($suffix),
        labelKey: @js($label),
        hintKey: {{ $hint ? "'".$hint."'" : 'null' }},
        enableDynamicSearch: @js($enableDynamicSearch),
        getMentionResultUsing: async (search) => {
            return await $wire.getMentionsItems(search,@js($statePath))
        },
    })">
    @include('filament-forms::components.rich-editor')
</div>
