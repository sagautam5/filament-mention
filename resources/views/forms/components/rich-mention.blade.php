@use('Filament\Support\Facades\FilamentAsset')
<div
    class=""
    ax-load
    ax-load-src="{{ FilamentAsset::getAlpineComponentSrc('tributejs', 'asmit/mention') }}"
    x-data="mention({
    fieldName: '{{ $getId() }}',
    mentionableItems: {{json_encode($getMentionableItems())}},
    triggerWith: '{{$triggerWith()}}',
    pluck: '{{$getPluck()}}',
    avatar: '{{$getAvatar()}}',
    })"
    x-ingore
>
<style type="text/css">
    .tribute-container {
        min-width: 250px;
        max-height: 20rem;
        padding: .4rem;
        margin-top: 1rem;
        border-radius: 15px;
        overflow: hidden;
        display: block !important;
        opacity: 0;
        pointer-events: none;
        transform: translateY(2rem);
        background-color: rgba(255,255,255,0.85);
        box-shadow: 0 10px 30px rgba(0,0,20,.2),0 2px 10px rgba(0,0,20,.05),inset 0 -1px 2px hsla(0,0%,100%,.025);
        backdrop-filter: saturate(1.5) blur(20px);
        -webkit-backdrop-filter: saturate(1.5) blur(20px);
        transition: all .15s ease-in-out;
    }
    .tribute-container > ul { 
        max-height: calc(20rem - 0.8rem);
        overflow: auto;
    }
    .tribute-container.tribute-active {
        opacity: 100;
        pointer-events: auto;
        transform: translateY(0);
    }
    .mention-item {
        display: flex;
        align-items: center;
        gap: calc(0.25rem * 1.5);
        padding: calc(0.25rem * 1.5);
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        border-radius: 14px;
    }
    .highlight .mention-item {
        background-color: #f1f5f9;
    }
    .mention-item__avatar {
        min-height: calc(0.25rem * 7);
        min-width: calc(0.25rem * 7);
        height: calc(0.25rem * 7);
        width: calc(0.25rem * 7);
        border-radius: calc(infinity * 1px);
        background-color: #cbd5e1;
        overflow: hidden;
        font-size: 0;
        text-align: center;
        text-wrap: nowrap;
        text-overflow: ellipsis;
        white-space: nowrap;
        position: relative;
    }
    .mention-item__info {
        display: flex;
        flex-direction: column;
    }
    .mention-item__info-name {
        font-size: 0.75rem;
        font-weight: 600;
        line-height: calc(1 / 0.75);
    }
    .mention-item__info-email {
        font-size: 12px;
        margin-top: calc(0.25rem * -0.5);
        opacity: 0.75;
    }
    .no-match {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8 0;
        font-weight: 500;
        color: rgba(0,0,0,.5);
    }
</style>
@include('filament-forms::components.rich-editor')
</div>
