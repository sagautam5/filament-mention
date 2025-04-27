/**
 * Sets up event listeners for the tribute instance
 * @param {HTMLElement} targetElement - The element to attach the event listeners to
 * @param {Tribute} tribute - The tribute instance
 */
export function setupEventListeners(targetElement, tribute) {
    targetElement.addEventListener("tribute-active-true", function () {
        tribute.menu.classList.add('tribute-active');
    });

    targetElement.addEventListener("tribute-active-false", function () {
        tribute.menu.classList.remove('tribute-active');
    });

    targetElement.addEventListener("keydown", function (event) {
        if (!tribute.isActive) return;

        const activeItem = tribute.menu.querySelector(".highlight");
        if (!activeItem) return;

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