import Tribute from "tributejs";

export function mention({
                            fieldName,
                            mentionableItems,
                            triggerWith,
                            pluck,
                            avatar,
                        }) {
    return {
        fieldName,
        pluck,
        avatar,
        init() {
            const id = this.fieldName;
            const tribute = new Tribute({
                trigger: triggerWith,
                values: function (text, cb) {
                    const items = mentionableItems.filter(user =>
                        user.key.includes(text) || user.name.includes(text)
                    );
                    cb(items);
                },
                menuItemTemplate: function (item) {
                    return `
                        <div style="display: flex; align-items: center;">
                            ${avatar ? `<img src="${item.original.image}" alt="${item.original.key}" style="width: 24px; height: 24px; border-radius: 50%; margin-right: 8px;" />` : ''}
                            <div>
                                <div style="font-weight: bold;">${item.original.name}</div>
                                <div style="font-size: 0.8em; color: #666;">@${item.original.key}</div>
                            </div>
                        </div>
                    `;
                },
                selectTemplate: function (item) {
                    if (typeof item === "undefined") return null;
                    return `<a href="${item.original.link}(--${item.original[pluck]}--)" data-trix-attribute="bold">@${item.original.key}</a>`;
                },
            });

            tribute.attach(document.getElementById(id));
        }
    };
}

export function fetchMention({
                            fieldName,
                            mentionableItems,
                            triggerWith,
                            pluck,
                            avatar,
                        }) {
    return {
        fieldName,
        mentionableItems,
        pluck,
        avatar,
        init() {
            const id = this.fieldName;
            const alpine = this.$wire;
            const tribute = new Tribute({
                trigger: triggerWith,
                values: function (text, cb) {
                    alpine.getMentionableItems(text).then(items => {
                        cb(items);
                    })

                },
                menuItemTemplate: function (item) {
                    return `
                        <div style="display: flex; align-items: center;">
                            ${avatar ? `<img src="${item.original.image}" alt="${item.original.key}" style="width: 24px; height: 24px; border-radius: 50%; margin-right: 8px;" />` : ''}
                            <div>
                                <div style="font-weight: bold;">${item.original.name}</div>
                                <div style="font-size: 0.8em; color: #666;">@${item.original.key}</div>
                            </div>
                        </div>
                    `;
                },
                selectTemplate: function (item) {
                    if (typeof item === "undefined") return null;
                    return `<a href="${item.original.link}(--${item.original[pluck]}--)" data-trix-attribute="bold">@${item.original.key}</a>`;
                },
            });

            tribute.attach(document.getElementById(id));
        }
    };
}
