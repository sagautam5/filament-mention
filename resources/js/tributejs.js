// /components/my-component.js
import Tribute from "tributejs";

export default function mention({
                                    fieldName,
                                    mentionableItems,
    triggerWith,
    pluck,
                                }) {
    return {
        fieldName,
        mentionableItems,
        pluck,
        init() {
            const id = this.fieldName;
            const tribute = new Tribute({
                trigger: triggerWith,
                values: function (text, cb) {
                    // Call the getMentionsItems method to filter users
                    const items = mentionableItems // Get all items initially
                        .filter(user => user.key.includes(text) || user.name.includes(text)); // Filter based on text input
                    cb(items);
                },
                menuItemTemplate: function (item) {
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

                selectTemplate: function (item) {
                    if (typeof item === "undefined") return null;
                    return `<a href="${item.original.link}(--${item.original[pluck]}--)" data-trix-attribute="bold">@${item.original.key}</a>`;
                },
            });

            tribute.attach(document.getElementById(id));
        }
    }
}
