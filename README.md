# CNO News Plugin

A simple, lightly opinionated WordPress Plugin for Choctaw News Post Types.

This plugin uses 2 CPTs, News and Boilerplates, to handle news posts, alongside ACF Pro and Bootstrap.

## Dependencies

This plugin assumes the following are already loaded in a theme:

-   Bootstrap
-   ACF Pro

## The Post Types

-   Inits cpts (news & boilerplates) with prefixes (i.e. `choctaw-boilerplates`).
-   Adds the News to default WordPress search query
-   Inits templates (`archive` and `single`) with a template part for the post preview.
-   Templates can be overriden in theme folder.
    -   Files must be located at `theme/templates/choctaw-file-name.php` where "file-name" matches the files in the plugin's `templates` folder

## ACF

### Fields

Inits ACF fields and classes with php. If you'd prefer to use the ACF GUI, see `inc/acf/readme.md` for how to disable this if you want to import json instead. The json is stored in this repo.

### Classes

The Classes escape all user-submitted input and provide a consisten, WordPress-like API (with familiar function naming conventions like `get_the_photo` and `the_photo` to `return` or `echo`, respectively).

Some of these functions are wrappers for existing WP functions: **this is by design** _(see v1.0 Dev Note below)._

## External Scripts

The plugin registers the [lite-vimeo](https://www.npmjs.com/package/@slightlyoff/lite-vimeo) package with an id of "cno-news", but does not enqueue it. Enqueuing is handled at the top of `single-choctaw-news.php` file.

Because this plugin assumes the use of this package, the ACF field for the video is a `number` input with a prefix of `https://vimeo.com/` to encourage content managers to only input the id. The instructions also declare that vimeo videos _must_ be public.

# Changelog

## v1.0

-   First build!

### Dev Note:

The `ACF_Image` class is loaded, but is yet unused. May be used in future versions (if/where "Featured Image" is not used). If your project also uses this class, you should wrap the class initialization in a conditional:

```php
if ( ! class_exists( 'ACF_Image' ) ) {
	// require_once the path to the `ACF_Image` class
}
```

This will load the class in a type of "no-conflict" mode.
