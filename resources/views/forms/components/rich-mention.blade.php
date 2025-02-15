@use('Filament\Support\Facades\FilamentAsset')
<div
    ax-load
    ax-load-src="{{ FilamentAsset::getAlpineComponentSrc(id:'asmit-filament-mention', package: 'asmit/filament-mention') }}"
    x-load-css="[@js(FilamentAsset::getStyleHref(id:'asmit-filament-mention', package: 'asmit/filament-mention'))]"
    x-data="mention({
    fieldName: '{{ $getId() }}',
    mentionableItems: {{ json_encode($getMentionableItems()) }},
    triggerWith: '{{ $triggerWith() }}',
    pluck: '{{ $getPluck() }}',
    menuShowMinLength: '{{ $getMenuShowMinLength() }}',
    menuItemLimit: '{{ $getMenuItemLimit() }}',
    lookupKey: '{{ $getLookupKey() }}',
    })"
    x-ingore
>
@include('filament-forms::components.rich-editor')
</div>
