=== VC Extension Hover Image ===
Contributors: vluongo
Tags: wpbakery, hover image, image hover, image swap, page builder
Requires at least: 6.0
Tested up to: 7.2
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a Hover Image element to WPBakery Page Builder: an image that changes to a second image when the mouse moves over it.

== Description ==

Show a second image when visitors move the mouse over an image, for example a product seen from another side, or a photo before and after. The element works like the WPBakery Single Image, with an optional link.

= How it works =

1. Edit a page with WPBakery Page Builder and add the "Hover Image" element, in the "VC Ext Elements" category.
2. Choose the image to show and the one to show on hover.
3. Optionally add a link, the alignment, an animation and the Design Options.

= Features =

* Works with WPBakery Page Builder installed as a plugin or included in your theme.
* Responsive images: each screen gets the right image size, with the alternative text set in the Media Library.
* The hover image is already loaded when the mouse moves over the element, so it shows without delay.
* Works with the keyboard too: the hover image shows when the link gets focus.
* Lightweight: a few lines of CSS, no JavaScript.

This plugin is not affiliated with or endorsed by WPBakery.

== Installation ==

1. Install the plugin from Plugins > Add New, or upload the `vc-extension-hover-image` folder to `/wp-content/plugins/`.
2. Activate it. WPBakery Page Builder must be active too.
3. Add the "Hover Image" element to a page with WPBakery Page Builder.

== Frequently Asked Questions ==

= Can this plugin be used without WPBakery Page Builder? =

No, the element is part of WPBakery Page Builder, which must be active.

= Which images work best? =

Two images with the same size. If the hover image has a different shape, it is cropped to fit the first one.

= Is this plugin made by WPBakery? =

No, it is an independent plugin.

== Screenshots ==

1. The Hover Image element in the WPBakery element list.
2. The element settings.

== Changelog ==

= 2.0.0 =
* Tested up to WordPress 7.2; requires WordPress 6.0 and PHP 7.4.
* Security: the link was printed as written, so a user allowed to edit posts could add scripts to the page. The link is now escaped and unsafe links are dropped.
* Fix: PHP warnings on pages with the element, and an empty image when no image was chosen.
* Fix: the element was missing when WPBakery Page Builder is loaded by the theme instead of installed as a plugin.
* Fix: the Element ID option was ignored.
* Fix: deprecation notice with WPBakery Page Builder 9.
* The hover effect now uses CSS instead of inline JavaScript. Images are responsive and keep their alternative text.
* The link also keeps the "nofollow" option set in WPBakery.
* The element settings can be translated on translate.wordpress.org.

= 1.2.3 =
* Support for WordPress 5.9 added
* Minor bug fix

= 1.2.2 =
* Support for WordPress 5.8 added

= 1.2.1 =
* Support for WordPress 5.7 added

= 1.2.0 =
* Bug fix and added support for WordPress 5.6

= 1.1.0 =
* Bug fix

= 1.0.1 =
* Fix typo for brand violations

= 1.0 =
* Public release
