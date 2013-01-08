=== Widget Entries ===
Contributors: marquex
Donate link: http://marquex.posterous.com/pages/widget-entries
Tags:  widget, post type, sidebars, custom widgets, php, sidebar, widgets, personalize
Requires at least: 3.0
Tested up to: 3.1
Stable tag: trunk

Widget Entries plugin creates the Widget post-type in the administration area to make easier the edition of the text widgets, and it also register a new widget to import the widget entries easily.

== Description ==

The text widget that comes with Wordpress is one of the its most useful features because of its power and flexibility. It admits HTML code but it is not very comfortable to code in that small box, so that is the reason because some WYSIWYG widgets were created some later, you can add images, and format the text easily.

But widgets are pieces of our sites intended to change some often, and it would be nice to have revisions, or upload images just for them, use shortcodes... cutting the story short, to **handle widgets as they were posts**.

Widget Entries plugin creates the Widget post-type in the administration area to make easier the edition of the text widgets, and also register a new widget to import the widget entries easily.

This way of working has many advantages:

*	You can format your widget with the Wordpress editor.
*	You can upload images directly to be shown in your widgets.
*	You can have widgets drafts.
*	You can use shortcodes inside your widget.
*	You can get back to a previous version of your widgets thanks to the revision feature.
*	You can export your widgets contents.

And you have more benefits like **using php scripts inside the widgets**.

This is the best way to manage your widgets when your theme has several sidebars, or different sidebars for every page. I recommend to use the [Custom Sidebars plugin](http://wordpress.org/extend/plugins/custom-sidebars/) to create and assign sidebars to posts and pages.

This plugin uses the [vtardia's](http://profiles.wordpress.org/users/vtardia/) [Improved Include Page Plugin](http://wordpress.org/extend/plugins/improved-include-page/) to show the Widget posts. Thanks for his outstanding job.

Translations are welcome! I will write your name down here if you donate your translation work. Thanks very much to:

*	marquex - English
*	marquex - Spanish

== Installation ==

There are two ways of installing the plugin:

**From the [WordPress plugins page](http://wordpress.org/extend/plugins/)**

1. Download the plugin
2. Upload the `widget-entries` folder to your `/wp-content/plugins/` directory.
3. Active the plugin in the plugin menu panel in your administration area.

**From inside your WordPress installation, in the plugin section.**

1. Search for widget entries plugin
2. Download it and then active it.

Once, you have the plugin activated, you will find a new post-type called 'Widgets' in your menu. There you will be able to create and manage your custom widgets.
A new widget will be able in Appearance >> Widgets menu, called 'Widget Entry' that allows to use all the widgets entries created.

== Frequently Asked Questions ==

= How can I use PHP inside my widgets? =

It's possible to add some scripting to your widgets using the shortcodes `[php][/php]`. All the text inside those tags will be interpreted as PHP code.
The PHP execution is based in the exceptional plugin [Allow PHP in Post and Pages](http://wordpress.org/extend/plugins/allow-php-in-posts-and-pages/). Have a look to its page for further usage instructions.

= I want my widget to display the message `[php]Hola amigo[/php]`. How can I do it? =

Use the shortcodes `[php off][/php]`: If you write `[php off]Hola amigo[/php]` the widget will display `[php]Hola amigo[/php]`.

= Why the `[php]` shortcodes are not working on my posts or pages? =

Widget Entries does not aim to give the inline php execution feature to the posts and pages. To do so I recommend to use [Allow PHP in Post and Pages plugin](http://wordpress.org/extend/plugins/allow-php-in-posts-and-pages/).

= I have a lot of widget entries in my Wordpress, and selecting them for appearing in the sidebar is a pain, because the list is too long. How can i make it shorter? =

If you have created a lot of widget entries I'm sure you are not using them. You can unpublish (save as a draft) the ones that you don't use it anymore, and they will not appear in the select box of the widget.

= An editor user cannot create widget entries. What capability is needed to create them? =

The capability needed is 'edit_theme_options', the same that is needed to edit sidebar widgets. You can change the capability modifying the 'cap_required' variable at the begining of the plugin source.

= More info =

You can find further information on the [Widget Entries plugin web page](http://marquex.posterous.com/pages/widget-entries)


== Screenshots ==

1. screenshot-1.png The plugin main interface for managing the widgets. When installed you can see that a new Widgets menu is available in the left.
2. screenshot-2.png Editing a widget is as easy and powerful as editing a post. You can see there are several revisions for the widget and some PHP code in it.
3. screenshot-3.png A new widget will be available in the sidebars configuration page that allows to insert the widget entries easily into your sidebars.
4. screenshot-4.png The widget entry in the front end.

== Changelog ==

= 0.1 =
Initial release