# CNO News Plugin

A simple, lightly opinionated WordPress Plugin for Choctaw News Post Types.

This plugin uses 2 CPTs, News and Boilerplates, to handle news posts, alongside ACF (Pro) and Bootstrap.

[Read more at this plugin's wiki](https://github.com/choctaw-nation/news-plugin/wiki)

## Quick Start

1. Grab the `choctaw-news-plugin.zip` file from [the latest release](https://github.com/choctaw-nation/cno-plugin-news/releases)
2. Install to your WordPress app

# Changelog

## v1.1.8

-   Fix WordPress Hook errors

## v1.1.7

-   Cleaned up Deployment's included files
-   Added license to bundle
-   Updated GH action name
-   Added ACF to required plugin list
-   Init/De-init plugin with WP hooks
-   Moved ACF classes from `/objects` to `/classes`

## v1.1.6

(_Actually_ deployment ready!)

-   Added Quick Start
-   Added the top-level php file so the plugin actually works.

## v1.1.5

-   Deployment ready!

## v1.1.4

-   Updated the News API
    -   Now handles adding default boilerplates
    -   Now handles injecting additional classes!

## v1.1.2

-   Fixes template loading issue

## v1.1.1

Fixes [#5](https://github.com/choctaw-nation/news-plugin/issues/5)

Registers 2 new image sizes, both at 16:9 ratio, but at 2x for Retina display support:

```php
add_image_size( 'choctaw-news-preview', 1392, 784 );
add_image_size( 'choctaw-news-single', 2592, 1458 );
```

## v1.1.0

-   Added Snippet for "Recent News" that displays posts flagged as "featured post", then rest of recent posts (similar to [choctawnation.com](choctawnation.com), [choctawnation.com/news](choctawnation.com/news), and [choctawnation.com/biskinik](choctawnation.com/biskinik) as of Nov 3, 2023).
-   Namespace ACF_Image

## v1.0.1

### Finished the API for the `\News` object.

-   Created all `get_the_*` and `the_*` functions for all properties
-   Updated "published date" functions to allow for article publish date being different than the original/external article publish date
-   Updated the 'single-choctaw-news' template to load each property that _could_ be set, as well as adding conditionals to only render markup if content exists (e.g. `has_boilerplates` will now conditionally render the boilerplate section, where previously an empty `<section class='boilerplates'></section>` would have been rendered).
-   Added comments

### Misc.

-   **Bug fix:** Updated footer calls from `wp_footer` to `get_footer`.
-   Migrated this file's header material to [the wiki](https://github.com/choctaw-nation/news-plugin/wiki).
-   Updated `Boilerplate` CPT args.

```php
'public'        => false,
'show_ui'       => true,
'show_in_menu'  => true,
'has_archive'   => false,
```

-   Also removed `Boilerplate` CPT support for featured image
-   Moved ACF & CPT files to their proper folders
-   Cleaned up the `plugin-logic` classes

## v1.0

-   First build!
