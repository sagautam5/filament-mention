/**
 * Filament Mention Plugin
 * A tribute.js integration for Filament PHP
 */

// Export the main API functions
export { mention } from './modules/staticMention';
export { fetchMention } from './modules/dynamicMention';

// Also export core functionality for advanced usage
export { createTribute } from './core/tributeConfig'; 