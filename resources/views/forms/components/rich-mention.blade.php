<div
    ax-load
    ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('tributejs', 'asmit/mention') }}"
    x-data="mention({ id: '{{ $getId() }}', mentionItems: {{json_encode($getMentionsItems())}} })"
    x-ingore
>
@include('filament-forms::components.rich-editor')
</div>
