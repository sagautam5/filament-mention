@use('Filament\Support\Facades\FilamentAsset')
@php
    $statePath = $getStatePath();
@endphp
<div
        id="add-list-{{ $getId() }}"
        ax-load
        ax-load-src="{{ FilamentAsset::getAlpineComponentSrc(id:'asmit-filament-mention', package: 'asmit/filament-mention') }}"
        x-load-css="[@js(FilamentAsset::getStyleHref(id:'asmit-filament-mention', package: 'asmit/filament-mention'))]"
        x-ignore
        x-data="fetchMention({
    fieldName: '{{ $getId() }}',
    triggerWith: {{ json_encode($getTriggerWith()) }},
    pluck: '{{ $getPluck() }}',
    menuShowMinLength: '{{ $getMenuShowMinLength() }}',
    menuItemLimit: '{{ $getMenuItemLimit() }}',
    lookupKey: '{{ $getLookupKey() }}',
    loadingItemString: '{{ trans('asmit-filament-mention::translations.loading') }}',
    noResultsString: '{{ trans('asmit-filament-mention::translations.no_results') }}',
    triggerConfigs: {{ json_encode($getTriggerConfigs()) }},
    prefix: '{{ $getPrefix() }}',
    suffix: '{{ $getSuffix() }}',
    titleField: '{{ $getTitleField() }}',
    hintField: {{ $getHintField() ? "'".$getHintField()."'" : 'null' }},
    enableDynamicSearch: true,
    getMentionResultUsing: async (search) => {
            return await $wire.getMentionsItems(search,@js($statePath))
        },
    })"
>
@include('filament-forms::components.rich-editor')
</div>
