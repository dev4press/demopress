=== DemoPress ===
Contributors: GDragoN
Donate link: https://plugins.dev4press.com/demopress/
Tags: dev4press, woocommerce, demo data, dummy data, dummy content, lorem ipsum, generator, bbpress, builder, random
Stable tag: 1.6.1
Requires at least: 5.3
Tested up to: 6.0
Requires PHP: 7.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Easy to use plugin for generating demo content for newly created websites used during the website development and testing, before real content is created and added.

== Description ==

Easy to use plugin for generating demo content for newly created websites used during the website development and testing, before real content is created and added. The plugin includes several generators and many builders that can create text, names, images and more. Plugin has 'local' and 'remote' builders. Remote builders depend on the online services to generate text or images. Local builders are PHP code based and can generate text, images, names and more. Most of the operations are based on the randomization.

= Home and GitHub =
* Learn more about the plugin: [DemoPress on Dev4Press](https://plugins.dev4press.com/demopress/)
* Contribute to plugin development: [DemoPress on GitHub](https://github.com/dev4press/demopress)

= Quick Overview Video =
https://www.youtube.com/watch?v=Eazmk93cO34

= WordPress default Generators =
* **Users**: randomize from select roles, domains for emails, password; generate name and about; use name for email and login.
* **Terms**: support for default and custom taxonomies; support for hierarchy; generate name and description.
* **Posts**: support for default and custom post types; support for hierarchy; generate, title, content, excerpt, publication date and author, featured image; assign terms.
* **Comments**: support for threaded comments; limit posts to get comments; generate content, comment authors information.

= bbPress Generators =
* **Forums**: support for forums hierarchy; generate forum title, content, publication date and author.
* **Topics**: generate title, content, publication date and author; assign topic tags; select forums for generated topics.
* **Replies**: generate title, content, author; date based on topic date; for topics from selected forums.

= More Generators =
* **WooCommerce Products**: support for generating products with basic additional settings

= Included Builders =
* **HTML**: generate HTML content. Includes local 'LoremIpsum' and remote 'LorIpsum.net' builders. Local builder can build content with basic block editor blocks.
* **Plain Text**: generate plain text content. Includes local 'LoremIpsum' and remote 'LorIpsum.net' builders.
* **Name**: generate names. Includes local 'LoremIpsum', 'Randomizer', and 'RandomNames' builders.
* **Title**: generate titles. Includes local 'LoremIpsum', 'Randomizer', and 'Listed' builders.
* **Term**: generate term names. Includes local 'LoremIpsum' and 'Randomizer' builders.
* **Image**: generate or retrieve images. Includes local 'Placeholder', and 'LocalStorage'; with remote 'Pexels.com' and 'Pixabay.com' builders (both require API keys to access).

= Additional Tools =
The plugin has tools to export and import plugin settings (not data, just settings). And, it has a tool to remove all the generated data, with options to choose data by type. When removing posts, it can also remove attached images.

= Documentation and Support =
To get help with the plugin, you can use WordPress.org support forums, or you can use Dev4Press.com support forums.

* Plugin Documentation: [Dev4Press Knowledge Base](https://support.dev4press.com/kb/plugin/demopress/)
* Support Forum: [Dev4Press Support](https://support.dev4press.com/forums/forum/plugins-free/demopress/)

== Installation ==
= General Requirements =
* PHP: 7.2 or newer

= WordPress Requirements =
* WordPress: 5.3 or newer

= Basic Installation =
* Plugin folder in the WordPress plugins folder must be `demopress`.
* Upload `demopress` folder to the `/wp-content/plugins/` directory.
* Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==
= Where can I configure the plugin? =
Open the WordPress 'Settings' menu, there you will find 'DemoPress' panel.

== Changelog ==
= 1.6.1 (2022.05.15) =
* New: tested with the WordPress 6.0

= 1.6 (2022.04.13) =
* New: posts generators: support for the post status
* New: bbpress generators: support for the post status

= 1.5 (2021.10.07) =
* New: generator: generate WooCommerce products
* Edit: for content generator show notice for taxonomies with no terms
* Edit: for content generator show only public taxonomies

= 1.4 (2021.08.03) =
* New: images placeholder generator: include rectangles
* New: images placeholder generator: use post name or image size

= 1.3.1 (2021.07.30) =
* Fix: some library files have wrong name case

= 1.3 (2021.07.10) =
* New: images placeholder generator: lighter and darker background color
* Edit: changed default priority for the main registration entry point
* Edit: various improvements to the base generators
* Edit: improved method for assigning custom taxonomy terms
* Fix: in some cases custom taxonomy terms not assigned
* Fix: minor issue in the generator registration method
* Fix: regression in the bbPress generator for topics

= 1.2 (2021.07.08) =
* Edit: comments: improved handling top level option with value 0
* Edit: author caching method accepts the list of roles to cache
* Edit: removed some unused files from the forms directory
* Edit: various PHP related improvements
* Edit: Dev4Press Library 3.5.3
* Fix: bbpress: forum roles for authors not taken into account
* Fix: query to get comments was not using approved flag value
* Fix: various typos and wrongly spelled words

= 1.1 (2020.11.25) =
* New: title builder: Listed - get titles from predefined list of titles
* New: image builder: LocalStorage - get images from predefined storage location
* Edit: various improvements to the core generator classes
* Fix: posts generator breaks when post type has no taxonomies
* Fix: few minor issues with the builder run methods default value

= 1.0 (2020.11.17) =
* First official release

== Upgrade Notice ==
= 1.6 =
Support for post statuses.

= 1.5 =
WooCommerce Products generator.

= 1.4 =
Images placeholder generator improvements.

= 1.3 =
Various improvements and fixes.

= 1.2 =
Various improvements and fixes.

= 1.1 =
New builders. Various improvements to generators. Few fixes.

== Screenshots ==
* Main generators panel
* Generator: Users
* Generator: Terms
* Generator: Posts
* Generator: Comments
* Generator: bbPress
* Generator progress
* Plugin settings
* Data removal tool
