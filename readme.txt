=== DosCero.Menu ===
Contributors: 2cero.com
Donate link: http://www.2cero.com/
Tags: menu, generator, admin, plugin, widget
Requires at least: 2.8.0
Tested up to: 3.1.3
Stable tag: trunk

A plugin to create a custom menu bar. Once you create the content, you can associate it with the menu. It's simple and easy to change the styles!

== Description ==

This plugin allows you to create and administrate menu bars in a website.

With it you will be able to create different menues and allow the content to be associated to fit your needs.

**Basic configuration**

**Create site content:** the posts, articles, categories, pages, etc. that will be later used to associate with this plugin

**Create the menu:** you can do this by using the **'ADD NEW'** button, there you will be able to start to relate the content previously created

**Insert the code:** insert the following PHP code where you want to show the menu (probably replacing the call to 'wp_nav_menu' in header.php):
`<?php DosCeroMenuPlugin::Render(ID); ?>;`

Where it says **'Render(ID)'** you must use the number that is displayed in the **'MENU ID'** item in each created menu.

**Another way to create a menu (using WIDGETS):** if you use the widget provided by this plugin **(2cero menu widget)**, it's not necessary to write any special code. The menubar will be shown where the widget was dropped.

**Style & design:** once you followed the previous steps you can give a different style to our menubar. To do that, you can go to the 'Styles' page and modify all the property that you need to make it look as you want. Each property is explained to make this easier.

== Installation ==

1. Upload `DosCero.Menu` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the newly created menues to use the plugin

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 1.0 =
* Initial commit

== Upgrade Notice ==

