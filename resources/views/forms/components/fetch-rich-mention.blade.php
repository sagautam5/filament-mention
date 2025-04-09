@use('Filament\Support\Facades\FilamentAsset')
<div
    id="add-list-{{ $getId() }}"
    wire:key="{{ rand() }}"
    ax-load
    ax-load-src="{{ FilamentAsset::getAlpineComponentSrc(id:'asmit-filament-mention', package: 'asmit/filament-mention') }}"
    x-load-css="[@js(FilamentAsset::getStyleHref(id:'asmit-filament-mention', package: 'asmit/filament-mention'))]"
    x-data="fetchMention({
        fieldName: '{{ $getId() }}',
       triggerWith: '{{ $triggerWith() }}',
        pluck: '{{ $getPluck() }}',
        menuShowMinLength: '{{ $getMenuShowMinLength() }}',
        menuItemLimit: '{{ $getMenuItemLimit() }}',
        lookupKey: '{{ $getLookupKey() }}',
    })"
    x-ignore
>
    @include('filament-forms::components.rich-editor')
</div>
