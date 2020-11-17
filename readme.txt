=== DemoPress ===
Contributors: GDragoN
Donate link: https://plugins.dev4press.com/demopress/
Tags: dev4press, demo data, dummy data, dummy content, lorem ipsum, generator, builder, random
Stable tag: 1.0
Requires at least: 5.0
Tested up to: 5.6
Requires PHP: 7.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Easy to use plugin for generating demo content for newly created websites used during the website development and testing, before real content is created and added.

== Description ==

Easy to use plugin for generating demo content for newly created websites used during the website development and testing, before real content is created and added. The plugin includes several generators and many builders that can create text, names, images and more. Plugin has 'local' and 'remote' builders. Remote builders depend on the online services to generate text or images. Local builders are PHP code based and can generate text, images, names and more. Most of the operations are based on the randomization.

= WordPress default Generators =
* **Users**: randomize from select roles, domains for emails, password; generate name and about; use name for email and login.
* **Terms**: support for default and custom taxonomies; support for hierarchy; generate name and description.
* **Posts**: support for default and custom post types; support for hierarchy; generate, title, content, excerpt, publication date and author, featured image; assign terms.
* **Comments**: support for threaded comments; limit posts to get comments; generate content, comment authors information.

= bbPress Generators =
* **Forums**: support for forums hierarchy; generate forum title, content, publication date and author.
* **Topics**: generate title, content, publication date and author; assign topic tags; select forums for generated topics.
* **Replies**: generate title, content, author; date based on topic date; for topics from selected forums.

= Included Builders =
* **HTML**: generate HTML content. Includes local 'LoremIpsum' and remote 'LorIpsum.net' builders. Local builder can build content with basic block editor blocks.
* **Plain Text**: generate plain text content. Includes local 'LoremIpsum' and remote 'LorIpsum.net' builders.
* **Name**: generate names. Includes local 'LoremIpsum', 'Randomizer', and 'RandomNames' builders.
* **Title**: generate titles. Includes local 'LoremIpsum' and 'Randomizer' builders.
* **Term**: generate term names. Includes local 'LoremIpsum' and 'Randomizer' builders.
* **Image**: generate or retrieve images. Includes local 'Placeholder' and remote 'Pexels.com' and 'Pixabay.com' builders (both require API keys to access).

= Additional Tools =
The plugin has tools to export and import plugin settings (not data, just settings). And, it has a tool to remove all the generated data, with options to choose data by type. When removing posts, it can also remove attached images.

= Home and GitHub =
* Learn more about the plugin: [DemoPress on Dev4Press](https://plugins.dev4press.com/demopress/)
* Contribute to plugin development: [DemoPress on GitHub](https://github.com/dev4press/demopress)

= Documentation and Support =
To get help with the plugin, you can use WordPress.org support forums, or you can use Dev4Press.com support forums.

* Plugin Documentation: [Dev4Press Knowledge Base](https://support.dev4press.com/kb/plugin/demopress/)
* Support Forum: [Dev4Press Support](https://support.dev4press.com/forums/forum/plugins-free/demopress/)

== Installation ==
= General Requirements =
* PHP: 7.0 or newer

= WordPress Requirements =
* WordPress: 5.0 or newer

= Basic Installation =
* Plugin folder in the WordPress plugins folder must be `demopress`.
* Upload `demopress` folder to the `/wp-content/plugins/` directory.
* Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==
= Where can I configure the plugin? =
Open the WordPress 'Settings' menu, there you will find 'DemoPress' panel.

== Changelog ==
= 1.0 (2020.11.17) =
* First official release

== Upgrade Notice ==
= 1.0 =
First official release

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
