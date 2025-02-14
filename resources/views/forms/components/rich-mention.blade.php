@use('Filament\Support\Facades\FilamentAsset')
<div
    class=""
    ax-load
    ax-load-src="{{ FilamentAsset::getAlpineComponentSrc(id:'asmit-mention', package: 'asmit/mention') }}"
    x-load-css="[@js(FilamentAsset::getStyleHref(id:'asmit-mention', package: 'asmit/mention'))]"
    x-data="mention({
    fieldName: '{{ $getId() }}',
    mentionableItems: {{ json_encode($getMentionableItems()) }},
    triggerWith: '{{ $triggerWith() }}',
    pluck: '{{ $getPluck() }}',
    avatar: '{{ $getAvatar() }}',
    menuShowMinLength: '{{ $getMenuShowMinLength() }}',
    menuItemLimit: '{{ $getMenuItemLimit() }}',
    lookupKey: '{{ $getLookupKey() }}',
    displayName: '{{ $getDisplayName() }}',
    })"
    x-ingore
>
@include('filament-forms::components.rich-editor')
</div>
