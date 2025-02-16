@use('Filament\Support\Facades\FilamentAsset')
<div
    id="add-list-{{ $getId() }}"
    ax-load
    ax-load-src="{{ FilamentAsset::getAlpineComponentSrc(id:'asmit-filament-mention', package: 'asmit/filament-mention') }}"
    x-load-css="[@js(FilamentAsset::getStyleHref(id:'asmit-filament-mention', package: 'asmit/filament-mention'))]"
    x-ignore
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
