import Tribute from "tributejs";
import {
    searchInObject,
    getDisplayValue,
    getTitleValue,
    getHintValue
} from '../utils/helpers';
import { setupEventListeners } from '../utils/eventListeners';

/**
 * Creates a tribute instance
 * @param {Object} config - The configuration object
 * @returns {Tribute} - The tribute instance
 */
export function createTribute({
    statePath,
    triggerWith,
    pluck,
    menuShowMinLength,
    menuItemLimit,
    lookupKey,
    loadingItemString,
    noResultsString,
    valuesFunction,
    triggerConfigs = null,
    prefix = '',
    suffix = '',
    titleField = 'title',
    hintField = null,
    enableDynamicSearch,
    getMentionResultUsing
}) {
    const targetElement = document.getElementById(statePath);

    if (!targetElement) {
        console.error(`Element with ID '${statePath}' not found in the DOM`);
        return null;
    }

    // Ensure menuShowMinLength is properly parsed as an integer with a default of 0
    const minLength = menuShowMinLength !== undefined && menuShowMinLength !== null ?
        parseInt(menuShowMinLength) : 0;

    // Ensure menuItemLimit is properly parsed as an integer with a default
    const itemLimit = menuItemLimit !== undefined && menuItemLimit !== null ?
        parseInt(menuItemLimit) : 10;

    // If triggerWith is a string (single trigger)
    if (!Array.isArray(triggerWith)) {
        return createSingleTriggerTribute({
            targetElement,
            triggerWith,
            pluck,
            minLength,
            itemLimit,
            lookupKey,
            loadingItemString,
            noResultsString,
            valuesFunction,
            prefix,
            suffix,
            titleField,
            hintField
        });
    }

    // If triggerWith is an array (multiple triggers)
    return createMultiTriggerTribute({
        targetElement,
        triggerWith,
        pluck,
        minLength,
        itemLimit,
        lookupKey,
        loadingItemString,
        noResultsString,
        valuesFunction,
        triggerConfigs,
        prefix,
        suffix,
        titleField,
        hintField
    });
}

/**
 * Creates a tribute instance with a single trigger
 * @param {Object} config - The configuration object
 * @returns {Tribute} - The tribute instance
 */
function createSingleTriggerTribute({
    targetElement,
    triggerWith,
    pluck,
    minLength,
    itemLimit,
    lookupKey,
    loadingItemString,
    noResultsString,
    valuesFunction,
    prefix,
    suffix,
    titleField,
    hintField
}) {
    const currentPrefix = prefix || '';
    const currentSuffix = suffix || '';

    const tribute = new Tribute({
        trigger: triggerWith,
        values: function (text, cb) {
            valuesFunction(text, function (items) {
                // Filter items by searching across all properties
                const filteredItems = items.filter(item => searchInObject(item, text));
                cb(filteredItems);
            });
        },
        menuShowMinLength: minLength,
        menuItemLimit: itemLimit,
        loadingItemTemplate: `<div class="loading-item">${loadingItemString}</div>`,
        lookup: function (item, mentionText) {
            return getDisplayValue(item, lookupKey);
        },
        menuContainer: document.body,
        menuItemTemplate: function (item) {
            const title = getTitleValue(item.original, titleField);
            const hint = getHintValue(item.original, hintField, currentPrefix, currentSuffix, lookupKey);

            return `
                <div class='mention-item'>
                    ${item.original.avatar ? `<img class="mention-item__avatar" src="${item.original.avatar}" alt="${title}"/>` : ''}
                    <div class='mention-item__info'>
                        <div class='mention-item__info-title'>${title}</div>
                        <div class='mention-item__info-hint'>${hint}</div>
                    </div>
                </div>
            `;
        },
        selectTemplate: function (item) {
            if (typeof item === "undefined") return null;

            const displayValue = getDisplayValue(item.original, lookupKey);

            if (item.original.url !== null) {
                return `<a href="${item.original.url}(--${item.original[pluck]}--)" data-trix-attribute="bold">${currentPrefix}${displayValue}${currentSuffix}</a>`;
            } else {
                return `${currentPrefix}${displayValue}${currentSuffix}`;
            }
        },
        noMatchTemplate: function () {
            return `<span class="no-match">${noResultsString}</span>`;
        }
    });

    tribute.attach(targetElement);
    setupEventListeners(targetElement, tribute);

    return tribute;
}

/**
 * Creates a tribute instance with multiple triggers
 * @param {Object} config - The configuration object
 * @returns {Tribute} - The tribute instance
 */
function createMultiTriggerTribute({
    targetElement,
    triggerWith,
    pluck,
    minLength,
    itemLimit,
    lookupKey,
    loadingItemString,
    noResultsString,
    valuesFunction,
    triggerConfigs,
    prefix,
    suffix,
    titleField,
    hintField
}) {
    const collections = [];

    for (let i = 0; i < triggerWith.length; i++) {
        const trigger = triggerWith[i];

        // Check if we have specific config for this trigger
        let currentLookupKey = lookupKey;
        let currentPrefix = prefix || '';
        let currentSuffix = suffix || '';
        let currentTitleField = titleField;
        let currentHintField = hintField;
        let currentConfig = null;

        if (triggerConfigs && triggerConfigs[trigger]) {
            currentConfig = triggerConfigs[trigger];

            if (currentConfig.lookupKey) {
                currentLookupKey = currentConfig.lookupKey;
            }

            if (currentConfig.prefix !== undefined) {
                currentPrefix = currentConfig.prefix;
            }

            if (currentConfig.suffix !== undefined) {
                currentSuffix = currentConfig.suffix;
            }

            // Support both camelCase and snake_case
            if (currentConfig.titleField) {
                currentTitleField = currentConfig.titleField;
            } else if (currentConfig.title_field) {
                currentTitleField = currentConfig.title_field;
            }

            // Support both camelCase and snake_case
            if (currentConfig.hintField) {
                currentHintField = currentConfig.hintField;
            } else if (currentConfig.hint_field) {
                currentHintField = currentConfig.hint_field;
            }
        }

        collections.push({
            trigger: trigger,
            values: function (text, cb) {
                // If we have a specific filter function for this trigger, use it
                if (currentConfig && typeof currentConfig.filter === 'function') {
                    return currentConfig.filter(text, cb, valuesFunction);
                }

                // Otherwise use the default filter
                valuesFunction(text, function (items) {
                    // Filter items by searching across all properties
                    const filteredItems = items.filter(item => searchInObject(item, text));
                    cb(filteredItems);
                });
            },
            lookup: function (item, mentionText) {
                return getDisplayValue(item, currentLookupKey);
            },
            menuShowMinLength: minLength,
            menuItemLimit: itemLimit,
            loadingItemTemplate: `<div class="loading-item">${loadingItemString}</div>`,
            menuContainer: document.body,
            menuItemTemplate: function (item) {
                const title = getTitleValue(item.original, currentTitleField);
                const hint = getHintValue(item.original, currentHintField, currentPrefix, currentSuffix, currentLookupKey);

                return `
                    <div class='mention-item'>
                        ${item.original.avatar ? `<img class="mention-item__avatar" src="${item.original.avatar}" alt="${title}"/>` : ''}
                        <div class='mention-item__info'>
                            <div class='mention-item__info-title'>${title}</div>
                            <div class='mention-item__info-hint'>${hint}</div>
                        </div>
                    </div>
                `;
            },
            selectTemplate: function (item) {
                if (typeof item === "undefined") return null;

                const displayValue = getDisplayValue(item.original, currentLookupKey);

                if (item.original.url !== null) {
                    return `<a href="${item.original.url}(--${item.original[pluck]}--)" data-trix-attribute="bold">${currentPrefix}${displayValue}${currentSuffix}</a>`;
                } else {
                    return `${currentPrefix}${displayValue}${currentSuffix}`;
                }
            },
            noMatchTemplate: function () {
                return `<span class="no-match">${noResultsString}</span>`;
            }
        });
    }

    const tribute = new Tribute({
        collection: collections
    });

    tribute.attach(targetElement);
    setupEventListeners(targetElement, tribute);

    return tribute;
} 