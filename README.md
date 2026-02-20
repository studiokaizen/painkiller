# Painkiller

Security and performance cleanup tweaks for WordPress, inspired by the Artisan theme cleanup routines.

## Features

- Disable feeds and XML-RPC
- Remove unwanted head tags and shortlinks
- Remove emoji and oEmbed scripts
- Restrict REST user endpoints for guests
- Dequeue core block/global/classic styles
- Remove asset version query strings on the front end
- Disable attachment pages and canonical redirects
- Discourage indexing in non-production environments
- Customize admin footer text

## Requirements

- WordPress 6.0+
- PHP 7.4+

## Installation

1. Upload the `painkiller` folder to `wp-content/plugins/`.
2. Activate **Painkiller** in WordPress Admin > Plugins.

## Notes

These tweaks are opinionated. Review the code before enabling in production.
