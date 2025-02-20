# BB Delete Cache (Updated)

**Contributors:** thierrypigot, wearewp, gbissland  
**Tags:** Beaver Builder, clear, cache, admin bar  
**Requires at least:** 5.0  
**Tested up to:** 6.4  
**Stable tag:** 1.1.1  
**Version:** 1.1.1  
**Text Domain:** bb-delete-cache  
**License:** GPLv2  
**License URI:** [http://www.gnu.org/licenses/gpl-3.0.html](http://www.gnu.org/licenses/gpl-3.0.html)  

---

## 🛠 What is this?

This plugin allows you to **clear Beaver Builder cache** directly from the WordPress admin bar.  

### 🚀 New in this version (1.1.1):
- ✅ **Restricted access to Editors and Admins only** (`edit_pages` capability required).  
- ✅ **Fixed nonce verification issues** for security.  
- ✅ **Improved sanitization & escaping** to follow WordPress coding standards.  
- ✅ **Now actively maintained by [Weave](https://github.com/weavedigitalstudio/)**.  

---

## ⚡️ Why Use This Plugin?

Beaver Builder caches assets to improve performance, but sometimes, **changes don’t appear instantly**. This plugin adds a **"Clear Cache" button** to the WordPress admin bar, so you can:  

- **Clear cache for the current page**  
- **Clear cache for the entire website**  
- **Avoid clearing Beaver Builder cache manually via FTP**  

---

## 🔄 Credits & Attribution

This plugin is based on the original **[BB Delete Cache](https://wordpress.org/plugins/bb-delete-cache/)** plugin by **Thierry Pigot** and **wearewp**, which looks like was abandoned in 2019.  

The Weave team has **updated and now actively maintains** this plugin to support **modern WordPress and PHP versions** while we continue ot use BB..

---

## 🔧 Installation from GitHub

When installing this plugin from GitHub:

1. Go to the [Releases](https://github.com/weavedigitalstudio/bb-delete-cache/releases) page
2. Download the latest release ZIP file
3. Extract the ZIP file on your computer
4. Rename the extracted folder to remove the version number
   (e.g., from `bb-delete-cache-1.1.1` to `bb-delete-cache`)
5. Create a new ZIP file from the renamed folder
6. In your WordPress admin panel, go to Plugins → Add New → Upload Plugin
7. Upload your new ZIP file and activate the plugin
8. Plugin should then auto-update moving forward if there are any changes.

**Note**: The folder renaming step is necessary for WordPress to properly handle plugin updates and functionality.

---

## 📌 Usage

- **Clear Cache for a Single Page:**  
  Click **"Clear This Page Cache"** in the admin bar while editing a post.  
  
- **Clear Cache for All Pages:**  
  Click **"Clear Beaver Builder Cache"** to reset cache site-wide.  

---

## 🏆 Maintained by:

- [Weave Digital Studio](https://github.com/weavedigitalstudio/)  
- [Gareth Bissland (gbissland)](https://github.com/gbissland)  

---

## 🛠 Technical Notes

- **Requires PHP 7.4+** (Fully compatible with PHP 8.x)  
- **Requires WordPress 5.0+**  
- **Tested with WordPress 6.4**  

---

### 🔹 What’s Changed in 1.1.1?

- ✅ **Only Editors & Admins can clear the cache** (`edit_pages` required).  
- ✅ **Improved security & optimized performance**.  
