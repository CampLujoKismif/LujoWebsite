# Dark Mode Implementation

## Overview

This document outlines the comprehensive dark mode implementation that has been added to the Camp LUJO-KISMIF website.

## Critical Fix for Dashboard Always Showing Dark Mode

**Issue:** The dashboard was always appearing in dark mode regardless of the user's preference.

**Root Cause:** 
1. **Flux uses its own localStorage key**: Flux stores themes in `localStorage` under `'flux.appearance'` (not `'theme'`)
2. **Flux defaults to 'system'**: The `@fluxAppearance` directive defaults to `'system'`, which reads your OS dark mode preference
3. **Our theme script was being overridden**: Flux's `@fluxAppearance` was running after our theme script and overriding it

**Solution:**
1. **Migrated to Flux's localStorage key**: Changed from `'theme'` to `'flux.appearance'` 
2. **Override Flux's 'system' default**: Set `'flux.appearance'` to `'light'` if it's missing or set to `'system'`
3. **Use Flux's API**: Our `toggleTheme()` function now uses `window.Flux.applyAppearance()` directly
4. **Script order matters**: Theme initialization runs BEFORE `@fluxAppearance`, then `toggleTheme()` runs AFTER
5. **Post-load enforcement**: Added check on DOMContentLoaded to ensure light mode stays the default

## What Was Fixed

### 1. Tailwind Configuration
**File:** `tailwind.config.js`

Added `darkMode: 'class'` strategy, which enables dark mode by adding/removing the `dark` class on the `<html>` element.

```javascript
export default {
  darkMode: 'class',  // ← Added this
  content: [...],
  theme: {...},
  plugins: [],
}
```

### 2. Theme Initialization Script
**Files:**
- `resources/views/partials/head.blade.php` (shared by authenticated layouts)
- `resources/views/home.blade.php`
- `resources/views/rentals.blade.php`
- `resources/views/elevate-week.blade.php`
- `resources/views/strive-week.blade.php`

Added a critical inline script that runs **before** the page renders to prevent flash of unstyled content (FOUC). This script:

- Checks `localStorage` for user's theme preference
- Falls back to system preference if no saved preference
- Applies the `dark` class to `<html>` element immediately
- Exposes global functions: `window.toggleTheme()` and `window.getTheme()`
- Listens for system theme changes

### 3. Theme Selector Menus
**Files:**
- `resources/views/components/layouts/app/sidebar.blade.php` (authenticated dashboard)
- `resources/views/components/layouts/public.blade.php` (public pages)

Added theme selector dropdowns to both desktop and mobile navigation:
- **Light Mode**: ☀️ Sun icon - forces light theme
- **Dark Mode**: 🌙 Moon icon - forces dark theme
- **System Mode**: 💻 Computer icon - follows OS preference
- Implemented with Flux dropdown components (dashboard) and Alpine.js (public)
- Smooth transitions between themes
- Shows current theme with appropriate icon
- Accessible with ARIA labels

### 4. Enhanced Dark Mode Styles
**File:** `resources/views/components/layouts/public.blade.php`

Updated the public layout with dark mode classes:
- Navigation bar: `bg-white dark:bg-gray-800`
- Body: `bg-gray-50 dark:bg-gray-900`
- Text colors: `text-gray-700 dark:text-gray-300`
- Logo: `text-blue-600 dark:text-blue-400`
- Mobile menu with proper dark mode styling

## How It Works

### Theme Persistence
1. When a user selects a theme, it's saved to `localStorage.setItem('flux.appearance', 'light' | 'dark' | 'system')`
2. On subsequent page loads, the saved preference is loaded immediately
3. If no preference is saved, **light mode is used as the default**
4. **System mode is now supported** - if selected, it will follow your OS dark mode preference
5. We use Flux's built-in `window.Flux.applyAppearance()` function for compatibility

### Theme Functions
```javascript
// Quick toggle between light and dark (skips system)
window.toggleTheme()

// Set a specific theme
window.setTheme('light')   // Force light mode
window.setTheme('dark')    // Force dark mode  
window.setTheme('system')  // Use OS preference

// Get current theme
window.getTheme() // Returns 'light', 'dark', or 'system'

// Check localStorage directly
localStorage.getItem('flux.appearance') // 'light', 'dark', or 'system'
```

### No Flash of Unstyled Content
The script runs **synchronously** in the `<head>` before any content renders, ensuring:
- No visible theme switch on page load
- Consistent theme across all pages
- Instant theme application

## Browser Support

- ✅ Modern browsers (Chrome, Firefox, Safari, Edge)
- ✅ System preference detection via `prefers-color-scheme`
- ✅ LocalStorage for persistence
- ✅ Graceful fallback if JavaScript is disabled (defaults to light mode)

## Where to Find Theme Selectors

- **Desktop Dashboard**: Bottom left of the sidebar (above your profile menu) - click the "Theme" button to open dropdown
- **Mobile Dashboard**: Top right of the header - click the sun/moon icon to open dropdown
- **Public Pages**: Navigation bar - click the sun/moon icon to reveal dropdown menu

Each selector offers three options:
1. ☀️ **Light** - Always use light mode
2. 🌙 **Dark** - Always use dark mode  
3. 💻 **System** - Automatically follow your OS settings

## Testing Checklist

- [ ] Select each theme option (Light, Dark, System) on public pages
- [ ] Select each theme option on authenticated dashboard  
- [ ] Reload page and verify theme persists
- [ ] Test System mode: change your OS dark mode and verify it switches automatically
- [ ] Test on mobile devices
- [ ] Verify no flash of wrong theme on page load
- [ ] Verify theme persists across different pages

## Future Enhancements

1. Add theme toggle to standalone pages (welcome.blade.php if needed)
2. Add smooth color transitions during theme switch
3. Add theme preference to user profile settings
4. Consider adding a "system" option in addition to light/dark

## Troubleshooting

### Theme not persisting across pages
- Check browser console for localStorage errors
- Verify all pages include the theme initialization script

### Flash of wrong theme on load
- Ensure the theme script is in `<head>` **before** any CSS
- Verify script is not marked as `defer` or `async`

### Dashboard always in dark mode
**THIS IS THE MAIN FIX!** Run these commands in your browser console:

```javascript
// Clear both old and new theme keys
localStorage.removeItem('theme');
localStorage.removeItem('flux.appearance');

// Refresh the page
location.reload();
```

After refreshing, the dashboard should load in **light mode**. The theme toggle should now work correctly.

If it's still in dark mode:
1. Open browser DevTools (F12)
2. Go to Application tab → Local Storage → http://localhost
3. Look for `flux.appearance` - if it says `'system'` or `'dark'`, delete it
4. Refresh the page

### Dark mode styles not applying
- Rebuild Tailwind CSS: `npm run build` or `npm run dev`
- Clear browser cache
- Verify `darkMode: 'class'` is in tailwind.config.js

### Toggle button not working
- Check browser console for JavaScript errors
- Verify `window.toggleTheme()` is available
- Ensure Alpine.js is loading properly (for navigation components)

