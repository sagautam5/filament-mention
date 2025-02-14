import Tribute from "tributejs";

function generateMenuItemTemplate(item, avatar) {
    return `
        <div class='mention-item'>
            ${avatar ? `<img class="mention-item__avatar" src="${item.original.image}" alt="${item.original.key}"/>` : ''}
            <div class='mention-item__info'>
                <div class='mention-item__info-name'>${item.original.name}</div>
                <div class='mention-item__info-email'>@${item.original.key}</div>
            </div>
        </div>
    `;
}

function generateSelectTemplate(item, pluck) {
    if (typeof item === "undefined") return null;
    return `<a href="${item.original.link}(--${item.original[pluck]}--)" data-trix-attribute="bold">@${item.original.key}</a>`;
}

function createTribute({ fieldName, triggerWith, pluck, avatar, valuesFunction }) {
    const targetElement = document.getElementById(fieldName);
    const tribute = new Tribute({
        trigger: triggerWith,
        values: valuesFunction,
        menuItemTemplate: (item) => generateMenuItemTemplate(item, avatar),
        selectTemplate: (item) => generateSelectTemplate(item, pluck),
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
