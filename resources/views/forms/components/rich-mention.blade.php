@include('filament-forms::components.rich-editor')
@once
    @push('scripts')
        @script
        <script>
            window.addEventListener('load', () => {
                const id = '{{ $getId() }}';
                const tribute = new Tribute({
                    values: function (text, cb) {
                        if(!!@js($getModelClass()) && !!@js($getKey())) {
                            console.log($wire.getMentionUser(@js($getModelClass()), @js($getKey())));
                        }
                        // Call the getMentionsItems method to filter users
                        const items = @json($getMentionsItems('')) // Get all items initially
                    .filter(user => user.key.includes(text) || user.name.includes(text)); // Filter based on text input
                        cb(items);
                    },

                    menuItemTemplate: function(item) {
                        return `
                            <div style="display: flex; align-items: center;">
                                <img src="${item.original.image}" alt="${item.original.key}"
                                     style="width: 24px; height: 24px; border-radius: 50%; margin-right: 8px;" />
                                <div>
                                    <div style="font-weight: bold;">${item.original.name}</div>
                                    <div style="font-size: 0.8em; color: #666;">@${item.original.key}</div>
                                </div>
                            </div>
                        `;
                    },

                    selectTemplate: function(item) {
                        if (typeof item === "undefined") return null;
                        return `<a href="${item.original.link}(--${item.original.id}--)" data-trix-attribute="bold">@${item.original.key}</a>`;
                    },
                });

                tribute.attach(document.getElementById(id));
            });
        </script>
        @endscript
    @endpush
@endonce
