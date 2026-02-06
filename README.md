# SouqMetrics – WooCommerce Attribution Plugin

This plugin captures marketing attribution data (UTMs and click IDs) and attaches it to WooCommerce orders.

## What it does
- Captures UTM parameters
- Captures click IDs (fbclid, gclid, ttclid)
- Stores attribution data on WooCommerce orders
- Works with paid orders and Cash on Delivery
- Requires no configuration

## Installation
1. Download this repository as a ZIP
2. Go to WordPress Admin → Plugins → Add New → Upload Plugin
3. Upload the ZIP file
4. Activate the plugin

## Captured fields
- utm_source
- utm_medium
- utm_campaign
- utm_content
- utm_term
- fbclid
- gclid
- ttclid
- referrer

## Notes
- This plugin is read-only and does not send data externally
- Data is stored as order meta and can be accessed via the WooCommerce REST API

## License
GPL-2.0
