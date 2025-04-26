import Tribute from "tributejs";

function generateMenuItemTemplate(item, lookupKey) {
    return `
        <div class='mention-item'>
            ${item.original.avatar ? `<img class="mention-item__avatar" src="${item.original.avatar}" alt="${item.original[lookupKey]}"/>` : ''}
            <div class='mention-item__info'>
                <div class='mention-item__info-name'>${item.original.display_name}</div>
                <div class='mention-item__info-email'>@${item.original[lookupKey]}</div>
            </div>
        </div>
    `;
}

function generateSelectTemplate(item, pluck, lookupKey) {
    if (typeof item === "undefined") return null;
    return `<a href="${item.original.url}(--${item.original[pluck]}--)" data-trix-attribute="bold">@${item.original[lookupKey]}</a>`;
}

function createTribute({ statePath: statePath, triggerWith, pluck, menuShowMinLength, menuItemLimit, lookupKey, valuesFunction }) {
    const targetElement = document.getElementById(statePath);
    const tribute = new Tribute({
        trigger: triggerWith,
        values: valuesFunction,
        menuShowMinLength: menuShowMinLength,
        menuItemLimit: menuItemLimit,
        loadingItemTemplate: `<div class="loading-item">Loading...</div>`,
        lookup: lookupKey,
        menuContainer: document.body,
        menuItemTemplate: (item) => generateMenuItemTemplate(item, lookupKey),
        selectTemplate: (item) => generateSelectTemplate(item, pluck, lookupKey),
        noMatchTemplate: () => `<span class="no-match">No results found</span>`
    });
    tribute.attach(targetElement);
    targetElement.addEventListener("tribute-active-true", () => tribute.menu.classList.add('tribute-active'));
    targetElement.addEventListener("tribute-active-false", () => tribute.menu.classList.remove('tribute-active'));

    targetElement.addEventListener("keydown", (event) => {
        if (!tribute.isActive) return;

        const activeItem = tribute.menu.querySelector(".highlight");
        if (!activeItem) return;

        const menu = tribute.menu;

        if (event.key === "ArrowDown") {
            // Scroll down to the next item
            const nextItem = activeItem.nextElementSibling;
            if (nextItem) {
                nextItem.scrollIntoView({ behavior: "smooth", block: "nearest" });
            }
        } else if (event.key === "ArrowUp") {
            // Scroll up to the previous item
            const prevItem = activeItem.previousElementSibling;
            if (prevItem) {
                prevItem.scrollIntoView({ behavior: "smooth", block: "nearest" });
            }
        }
    });
}

export function mention({
    statePath,
    mentionableItems,
    triggerWith,
    pluck,
    menuShowMinLength,
    menuItemLimit,
    lookupKey,
    enableDynamicSearch,
    getMentionResultUsing
}) {
    return {
        statePath,
        pluck,
        menuShowMinLength,
        lookupKey,
        menuItemLimit,
        getMentionResultUsing,
        init() {
            createTribute({
                statePath: this.statePath,
                triggerWith,
                pluck,
                menuShowMinLength,
                lookupKey,
                menuItemLimit,
                valuesFunction: (text, cb) => {
                    if (enableDynamicSearch) {
                        this.getMentionResultUsing(text,this.statePath).then((response) => {
                            const items = response.filter(user =>
                                user[lookupKey].toLowerCase().includes(text.toLowerCase())
                            );
                            cb(items);
                        });
                    } else {
                        const items = mentionableItems.filter(user =>
                            user[lookupKey].toLowerCase().includes(text.toLowerCase())
                        );
                        cb(items);
                    }
                }
            });
        }
    };
}

export function fetchMention({
    statePath,
    triggerWith,
    pluck,
    menuShowMinLength,
    menuItemLimit,
    lookupKey,
    getMentionResultUsing

}) {
    return {
        statePath,
        pluck,
        menuShowMinLength,
        menuItemLimit,
        lookupKey,
        getMentionResultUsing,
        init() {

            const alpine = this.$wire;
            createTribute({
                statePath: this.statePath,
                triggerWith,
                pluck,
                menuShowMinLength,
                menuItemLimit,
                lookupKey,
                valuesFunction: async (text, cb) => {
                    await this.getMentionResultUsing(text, 'data.bio').then((response) => {
                        const items = response.filter(user =>
                            user[lookupKey].toLowerCase().includes(text.toLowerCase())
                        );
                        cb(items);
                    });
                }
            });
        }
    };
}
