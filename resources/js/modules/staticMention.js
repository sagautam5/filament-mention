import { createTribute } from '../core/tributeConfig';

/**
 * Creates a mention component with static items
 * @param {Object} config - The configuration object
 * @returns {Object} - Alpine.js component
 */
export function mention({
    statePath,
    mentionableItems,
    triggerWith,
    pluck,
    menuShowMinLength,
    menuItemLimit,
    lookupKey,
    loadingItemString,
    noResultsString,
    triggerConfigs = null,
    prefix = '',
    suffix = '',
    titleField = 'title',
    hintField = null,
    enableDynamicSearch,
    getMentionResultUsing
}) {
    return {
        statePath,
        pluck,
        menuShowMinLength,
        lookupKey,
        menuItemLimit,
        enableDynamicSearch,
        getMentionResultUsing,
        init() {
            createTribute({
                statePath: this.statePath,
                triggerWith,
                pluck,
                menuShowMinLength,
                lookupKey,
                menuItemLimit,
                loadingItemString,
                noResultsString,
                triggerConfigs,
                prefix,
                suffix,
                titleField,
                hintField,
                enableDynamicSearch,
                getMentionResultUsing,
                valuesFunction: function (text, cb) {
                    cb(mentionableItems);
                }
            });
        }
    };
} 