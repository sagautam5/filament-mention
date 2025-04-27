/**
 * Helper function to search across all object properties
 * @param {Object} item - The item to search in
 * @param {string} searchText - The text to search for
 * @returns {boolean} - Whether the item contains the search text
 */
export function searchInObject(item, searchText) {
    if (!searchText) return true;

    const searchLower = searchText.toLowerCase();

    // Search in all string properties of the object
    for (const key in item) {
        if (typeof item[key] === 'string') {
            const valueToSearch = item[key].toLowerCase();

            if (valueToSearch.includes(searchLower)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Helper function to get display value based on lookupKey or default
 * @param {Object} item - The item to get the display value from
 * @param {string} currentLookupKey - The lookup key to use
 * @returns {string} - The display value
 */
export function getDisplayValue(item, currentLookupKey) {
    // If lookupKey is specified and exists in the item, use it
    if (currentLookupKey && item[currentLookupKey]) {
        return item[currentLookupKey];
    }

    // Otherwise, try common fields in order
    if (item.value) return item.value;
    if (item.username) return item.username;
    if (item.name) return item.name;
    if (item.key) return item.key;

    // If none of the above, use the first string property
    for (const key in item) {
        if (typeof item[key] === 'string') {
            return item[key];
        }
    }

    // Last resort
    return "Unknown";
}

/**
 * Helper function to get title value
 * @param {Object} item - The item to get the title value from
 * @param {string} field - The field to use
 * @returns {string} - The title value
 */
export function getTitleValue(item, field) {
    if (field && item[field]) {
        return item[field];
    }

    if (item.title) return item.title;
    if (item.name) return item.name;
    if (item.label) return item.label;

    return getDisplayValue(item, null);
}

/**
 * Helper function to get hint value
 * @param {Object} item - The item to get the hint value from
 * @param {string} field - The field to use
 * @param {string} currentPrefix - The prefix to use
 * @param {string} currentSuffix - The suffix to use
 * @param {string} lookupKey - The lookup key to use
 * @returns {string} - The hint value
 */
export function getHintValue(item, field, currentPrefix, currentSuffix, lookupKey) {
    if (field && item[field]) {
        return `${currentPrefix}${item[field]}${currentSuffix}`;
    }

    const displayValue = getDisplayValue(item, lookupKey);
    return `${currentPrefix}${displayValue}${currentSuffix}`;
} 