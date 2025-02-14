import Tribute from "tributejs";

function generateMenuItemTemplate(item, avatar, lookupKey, displayName) {
    return `
        <div class='mention-item'>
            ${avatar ? `<img class="mention-item__avatar" src="${item.original.image}" alt="${item.original[lookupKey]}"/>` : ''}
            <div class='mention-item__info'>
                <div class='mention-item__info-name'>${item.original[displayName]}</div>
                <div class='mention-item__info-email'>@${item.original[lookupKey]}</div>
            </div>
        </div>
    `;
}

function generateSelectTemplate(item, pluck, lookupKey) {
    if (typeof item === "undefined") return null;
    return `<a href="${item.original.url}(--${item.original[pluck]}--)" data-trix-attribute="bold">@${item.original[lookupKey]}</a>`;
}

function createTribute({ fieldName, triggerWith, pluck, avatar, menuShowMinLength, menuItemLimit, lookupKey, displayName, valuesFunction }) {
    const targetElement = document.getElementById(fieldName);
    const tribute = new Tribute({
        trigger: triggerWith,
        values: valuesFunction,
        menuShowMinLength: menuShowMinLength,
        menuItemLimit:menuItemLimit,
        loadingItemTemplate: `<div class="loading-item">Loading...</div>`,
        lookup: lookupKey,
        menuItemTemplate: (item) => generateMenuItemTemplate(item, avatar, lookupKey, displayName),
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
                            fieldName,
                            mentionableItems,
                            triggerWith,
                            pluck,
                            avatar,
                            menuShowMinLength,
                            menuItemLimit,
                            lookupKey,
                            displayName
                        }) {
    return {
        fieldName,
        pluck,
        avatar,
        menuShowMinLength,
        lookupKey,
        menuItemLimit,
        displayName,
        init() {
            createTribute({
                fieldName: this.fieldName,
                triggerWith,
                pluck,
                avatar,
                menuShowMinLength,
                lookupKey,
                displayName,
                menuItemLimit,
                valuesFunction: (text, cb) => {
                    const items = mentionableItems.filter(user =>
                        user[lookupKey].includes(text)
                    );
                    cb(items);
                }
            });
        }
    };
}

export function fetchMention({
                                 fieldName,
                                 triggerWith,
                                 pluck,
                                 avatar,
                                 menuShowMinLength,
                                 menuItemLimit,
                                 lookupKey,
                                 displayName
                             }) {
    return {
        fieldName,
        pluck,
        avatar,
        menuShowMinLength,
        menuItemLimit,
        lookupKey,
        displayName,
        init() {
            const alpine = this.$wire;
            createTribute({
                fieldName: this.fieldName,
                triggerWith,
                pluck,
                avatar,
                menuShowMinLength,
                menuItemLimit,
                lookupKey,
                displayName,
                valuesFunction: (text, cb) => {
                    alpine.getMentionableItems(text).then(items => {
                        cb(items);
                    });
                }
            });
        }
    };
}
