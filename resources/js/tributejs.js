import Tribute from "tributejs";

export default function mention({ id, mentionItems }) {
    return {
        id,
        mentionItems,

        init() {
            this.$nextTick(() => {
                const inputElement = document.getElementById(this.id);
                if (!inputElement) {
                    console.error(`[Tribute] Element with id '${this.id}' not found!`);
                    return;
                }

                const tribute = new Tribute({
                    values: (text, cb) => {
                        console.log("Searching:", text);

                        const lowerText = text.toLowerCase(); // Convert input to lowercase

                        const filteredItems = this.mentionItems.filter(user =>
                            user.key.toLowerCase().includes(lowerText) ||
                            user.name.toLowerCase().includes(lowerText)
                        );

                        cb(filteredItems);
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
                        if (!item) return null;
                        return `<a href="${item.original.link}(--${item.original.id}--)" data-trix-attribute="bold">@${item.original.key}</a>`;
                    },
                });

                tribute.attach(inputElement);
            });
        }
    };
}
