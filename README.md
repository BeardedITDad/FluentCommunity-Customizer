# Fluent Community code modification snippets 
---
# WordPress + FluentCommunity Automation Snippets

This repository contains a collection of **custom code snippets** designed to enhance and automate WordPress sites running **FluentCommunity**, **Paid Memberships Pro**, and related tools.

These scripts solve real-world problems for site admins, from auto-generating usernames to setting default notification preferences for new spaces. All snippets are safe to copy and paste directly into your WordPress site’s `functions.php` file **(preferably inside a child theme — more on that below).**

---

## ⚙️ What’s Included

- ✅ Automatically assign notification preferences when a new space is created in FluentCommunity
- ✅ Auto-fill usernames in the `FirstName.LastName` format on checkout
- ✅ Default notification settings for new users
- ✅ Miscellaneous admin automations and fixes

---

## 📥 How to Use These Snippets

> These snippets are intended to be copy-pasted into your WordPress theme’s `functions.php` file.

### ⚠️ Use a Child Theme (Important!)

To avoid losing your changes when your theme updates, always use a **child theme**.

#### What is a child theme?
A child theme is a WordPress theme that inherits the functionality and styling of another theme (the “parent”), and allows you to safely make customizations.

#### Don’t have a child theme yet?
You can create one by following this guide:  
👉 [WordPress: How to Create a Child Theme](https://developer.wordpress.org/themes/advanced-topics/child-themes/)

Or use a plugin like:
- [Child Theme Configurator](https://wordpress.org/plugins/child-theme-configurator/)

---

### 🔧 Step-by-Step Instructions

#### 1. Open Your Child Theme’s `functions.php`

**Via WordPress dashboard:**
- Go to **Appearance > Theme File Editor**
- Choose your **child theme**
- Click on `functions.php` in the right-hand sidebar

**Or via FTP/file manager:**
- Navigate to:  
  `/wp-content/themes/your-child-theme/functions.php`

#### 2. Backup First (Optional but Smart)
Save a copy of your current `functions.php` before making changes.

#### 3. Copy a Snippet
Open the file from this repo that matches what you need and copy the code.

#### 4. Paste It In
Paste the code at the bottom of `functions.php` — make sure it’s inside the PHP block and **before any closing `?>` tag**, if present.

#### 5. Save the File
- Click **Update File** (in the WordPress editor), or
- Save/upload the file via FTP

That’s it! Your custom functionality is now live.

---



