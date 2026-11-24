=== DemoPress: Demo Content Generator ===
Contributors: GDragoN
Donate link: https://buymeacoffee.com/millan
Tags: dev4press, demo data, lorem ipsum, bbpress, woocommerce
Stable tag: 3.0
Requires at least: 6.2
Tested up to: 7.2
Requires PHP: 8.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Generate demo posts, users, terms, comments, and images for WordPress development and testing, plus sample WooCommerce and bbPress content.

== Description ==

Populate a WordPress development or staging site with content before the real material is ready. Generators determine what DemoPress creates, and builders determine how it creates the text, names, titles, and images. Each generator lets you choose how much content to create and which settings apply to the batch.

= What you can generate =

* **Users.** Choose roles, email domains, and password settings. DemoPress generates names and biographies and uses names to build usernames and email addresses.
* **Terms.** Generate names and descriptions for built-in and custom taxonomies, including hierarchical terms.
* **Posts and pages.** Create content for built-in and custom post types. Configure titles, content, excerpts, authors, publication dates, featured images, term assignments, and parent-child relationships where supported.
* **Comments.** Generate threaded comments for selected posts and populate comment content and author details.
* **bbPress.** Generate forums, topics, and replies when bbPress is active. Configure forum hierarchy, select forums for topics, and assign topic tags.
* **WooCommerce.** Generate simple products when WooCommerce is active. Choose physical or virtual products and configure downloads, prices, discounts, stock status, and SKUs.

= Builders for text, names, and images =

* **HTML and plain text.** Generate text locally with Lorem Ipsum. The HTML builder can produce basic block editor blocks.
* **Names.** Choose Random Names, Lorem Ipsum, or Randomizer.
* **Titles.** Use Lorem Ipsum or Randomizer, or supply your own titles with Listed Titles.
* **Terms.** Generate term names with Lorem Ipsum or Randomizer.
* **Images.** Generate placeholders with dimensions or custom text, use Local Storage, or retrieve images from Pexels or Pixabay. Pexels and Pixabay each require an API key.

= Cleanup and settings =

Use **Tools → Remove Generated Data** to select which generated content types to remove. When removing generated posts, you can also remove their attached images. **Tools → Export Settings** and **Tools → Import Settings** let you save and restore the plugin configuration.

= Documentation and support =

* [DemoPress on Dev4Press](https://www.dev4press.com/plugins/demopress/)
* [DemoPress on GitHub](https://github.com/dev4press/demopress)
* [Dev4Press Knowledge Base](https://www.dev4press.com/kb/plugin/demopress/)
* [Support on WordPress.org](https://wordpress.org/support/plugin/demopress/)
* [Dev4Press support forum](https://support.dev4press.com/forums/forum/plugins-free/demopress/)

== Installation ==

= Requirements =

* WordPress 6.2 or later and PHP 8.0 or later.
* The bundled libraries use PHP extensions including cURL, DOM, Fileinfo, Mbstring, and OpenSSL. For source installs, run `composer check-platform-reqs --no-dev` to check the full set.
* The Placeholder image builder requires GD. Rendering text or dimensions on placeholder images also requires FreeType support.

= Install and run your first batch =

1. Install DemoPress from the WordPress Plugins screen, or upload the packaged `demopress` folder to `wp-content/plugins/`.
2. Activate DemoPress, then open **Settings → DemoPress**.
3. In **Overview**, open a generator, choose the number of items to create, and select your builders and any other options.
4. Select **Run Generator** to start the batch and view its progress.

= Build from source =

1. In the `demopress` folder, run `composer install --no-dev` to install the PHP dependencies.
2. Use Node.js 20.19 or later in the 20.x series, 22.13 or later in the 22.x series, or Node.js 24 or later. Run `npm ci` to install the build tools.
3. Run `npm run build` to generate `build/admin.js` and `build/admin.css`. Run `npm run check` to check formatting, linting, and types, or `npm run format` to apply formatting.

Include the `vendor/` and `build/` directories when packaging DemoPress for distribution.

== Frequently Asked Questions ==

= Do I need API keys to generate content? =
Local builders such as Lorem Ipsum, Random Names, and Placeholder work without API keys. To use Pexels or Pixabay, enter the corresponding key under **DemoPress → Settings → API Keys**.

= How do I use my own images? =
Select the Local Storage image builder and place your files in the storage path shown in the generator. DemoPress moves each image it uses into the WordPress Media Library.

= How do I remove DemoPress content? =
Open **DemoPress → Tools → Remove Generated Data** and select the content types you want to delete. DemoPress can still identify generated records after you edit them.

== Changelog ==

= 3.0 (2026.12.01) =
* New: require PHP 8.0 and WordPress 6.2 or newer
* New: load dependencies with Composer
* New: build TypeScript and SCSS with Vite
* Edit: remove jQuery from the DemoPress admin script
* Edit: update the three generator libraries
* Edit: Dev4Press Library 5.6.5
* Remove: Loripsum.net builders (website is gone)
* Fix: restore generator cards on the overview
* Fix: generated block markup and empty text

= 2.0 (2024.08.20) =
* New: tested with WordPress up to 6.6
* New: tested with PHP up to 8.3
* New: plugin system requirements
* New: plugin interface aligned with the shared library
* Edit: various small tweaks and changes to the interface
* Edit: many tweaks and updates to the generators
* Edit: many updates related to the code syntax
* Edit: updated plugin screenshots on WordPress.org
* Edit: Dev4Press Library 5.0.1
* Del: removed a lot of the outdated styling
* Del: removed several unused and outdated files
* Fix: some assignment issues with Posts generator
* Fix: woocommerce products had invalid discount meta value

= 1.6.1 (2022.05.15) =
* New: tested with the WordPress 6.0

= 1.6 (2022.04.13) =
* New: posts generators: support for the post-status
* New: bbpress generators: support for the post-status

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
= 3.0 =
DemoPress 3.0 requires WordPress 6.2 or later and PHP 8.0 or later. It updates the bundled generators and restores the generator cards on the overview.

= 2.0 =
Big update with new interface, tweaks, and fixes.

= 1.6 =
Support for post statuses.

= 1.5 =
WooCommerce Products generator.

== Screenshots ==

1. Main generators panel
2. Users generator
3. Terms generator
4. Posts generator
5. Comments generator
6. bbPress generator
7. WooCommerce generator
8. Generator progress
9. Plugin settings
10. Data removal tool
