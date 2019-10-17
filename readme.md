# Heading Jumper

Contributors: peterste
Tags: table of contents, navigation,

Requires at least: 5.2

Tested up to: 5.2
Requires PHP: 7.1

Stable tag: 5.2

License: GPLv2 and later

License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays a navigation menu allowing user to jump to any heading in the page content.

## Description
Adds hierarchical \"table of contents\"-type functionality to any page on your WordPress site. Only top-level sections are initally visible, with show/hide buttons allowing your visitors to view an outline of the content in more detail. Heading Jumper can either display as a widget or at the top of the content.

## Installation
1. Upload `heading-jumper.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the \'Plugins\' menu in WordPress
3. Head over to Settings -> Reading to set a couple quick display options.

## Frequently Asked Questions
### Can I ditch the default css?
Yes. Add the following line to your theme\'s functions.php.
    `add_filter( \'disable_heading_jumper_css\', true);`

### Why isn\'t the widget showing up?
Remember that if you choose widget display you still need to add the widget to a sidebar to see it :)
== Screenshots ==
1. ![Heading Jumper on a page.](./heading-jumper-screenshot.png "Heading Jumper")

## Changelog
= 1.0.0 =
Nothing\'s changed yet!

## Upgrade Notice
= 1.0.0 =
First version.

## Credits
Built on [Wordpress Plugin Boilerplate](http://wppb.io/, "wordpress plugin boilerplate")
