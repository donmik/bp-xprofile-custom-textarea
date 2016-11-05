=== Bp Xprofile Custom Textarea ===
Contributors: donmik, johnywhy
Tags: buddypress, xprofile, fields, textarea, richtext
Requires at least: 4.0
Tested up to: 4.4
Stable tag: 0.2
License: GLPv2 or later

Replace Textarea buddypress field type with my own custom textarea.

== Description ==

= Buddypress required! (v2.0 at least) =

Replace Textarea buddypres field type with my own custom textarea. You will be able to disable
richtext editor if you want using a checkbox in admin.

== Installation ==

1. Upload the plugin to your 'wp-content/plugins' directory
2. Activate the plugin
3. Go to Users > Profile Fields
4. Create or Edit a field.
5. In Field Type select, select "Custom multi-line Text Area".
6. Check the checkbox "disable richtext".
7. Enjoy!

== Changelog ==

= 0.2 =
* Solve a bug, the content of the field was not being saved.

= 0.1.1 =
* Thanks again to johnywhy, I've modified my code to use the "bp_xprofile_is_richtext_enabled_for_field"
filter to disable richtext. Now the custom textarea class don't override two methods and is more
compatible with future releases of Buddypress.

= 0.1.0 =
* First release!