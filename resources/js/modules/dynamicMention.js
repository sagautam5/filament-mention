import { createTribute } from '../core/tributeConfig';

/**
 * Creates a mention component with dynamically fetched items
 * @param {Object} config - The configuration object
 * @returns {Object} - Alpine.js component
 */
export function fetchMention({
    statePath,
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
        menuItemLimit,
        lookupKey,
        enableDynamicSearch,
        getMentionResultUsing,
        init() {
            const alpine = this.$wire;
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
                    alpine.getMentionableItems(text).then(function (items) {
                        cb(items);
                    });
                }
            });
        }
    };
} 