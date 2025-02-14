@use('Filament\Support\Facades\FilamentAsset')
<div
    ax-load
    ax-load-src="{{ FilamentAsset::getAlpineComponentSrc('tributejs', 'asmit/mention') }}"
    x-data="fetchMention({
        fieldName: '{{ $getId() }}',
       triggerWith: '{{ $triggerWith() }}',
        pluck: '{{ $getPluck() }}',
        avatar: '{{ $getAvatar() }}',
        menuShowMinLength: '{{ $getMenuShowMinLength() }}',
        menuItemLimit: '{{ $getMenuItemLimit() }}',
        lookupKey: '{{ $getLookupKey() }}',
        displayName: '{{ $getDisplayName() }}',
    })"
    x-ignore
>
    @include('filament-forms::components.rich-editor')
</div>
