import Tribute from "tributejs";

function generateMenuItemTemplate(item, avatar) {
    return `
        <div style="display: flex; align-items: center;">
            ${avatar ? `<img src="${item.original.image}" alt="${item.original.key}" style="width: 24px; height: 24px; border-radius: 50%; margin-right: 8px;" />` : ''}
            <div>
                <div style="font-weight: bold;">${item.original.name}</div>
                <div style="font-size: 0.8em; color: #666;">@${item.original.key}</div>
            </div>
        </div>
    `;
}

function generateSelectTemplate(item, pluck) {
    if (typeof item === "undefined") return null;
    return `<a href="${item.original.link}(--${item.original[pluck]}--)" data-trix-attribute="bold">@${item.original.key}</a>`;
}

function createTribute({ fieldName, triggerWith, pluck, avatar, valuesFunction }) {
    const tribute = new Tribute({
        trigger: triggerWith,
        values: valuesFunction,
        menuItemTemplate: (item) => generateMenuItemTemplate(item, avatar),
        selectTemplate: (item) => generateSelectTemplate(item, pluck),
    });

    tribute.attach(document.getElementById(fieldName));
}

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
            createTribute({
                fieldName: this.fieldName,
                triggerWith,
                pluck,
                avatar,
                valuesFunction: (text, cb) => {
                    const items = mentionableItems.filter(user =>
                        user.key.includes(text) || user.name.includes(text)
                    );
                    cb(items);
                }
            });
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
        pluck,
        avatar,
        init() {
            const alpine = this.$wire;
            createTribute({
                fieldName: this.fieldName,
                triggerWith,
                pluck,
                avatar,
                valuesFunction: (text, cb) => {
                    alpine.getMentionableItems(text).then(items => {
                        cb(items);
                    });
                }
            });
        }
    };
}
