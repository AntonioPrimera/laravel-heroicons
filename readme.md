# Laravel HeroIcons

This is a simple, but opinionated package, useful for easily rendering hero icons.

As simple as this (in your blade file):

```html
@heroIcon('arrow-right')
```

This package provides you the option to easily include hero icons in your blade files, but also manipulate the
html in the icon svg.

HeroIcons are provided by the makers of tailwindcss, and you can find them here:
[https://heroicons.com/](https://heroicons.com/)

## Installation

Import the package in your Laravel project via composer:

`composer require antonioprimera/laravel-heroicons`

## Usage

### Blade directive

The package sets up a dedicated blade directive `@heroIcon('icon-name', 'icon-format')`. This creates an
icon instance and renders it. The first argument is the name (mandatory) and the second argument is the format,
which is optional and must be either 'solid' or 'outline'. If not provided, the default format is 'outline'.

```html
<div class="w-6">
    @heroIcon('arrow-down', 'outline')
</div>
<div class="w-6">
    @heroIcon('arrow-up')
</div>
```

This blade directive is created to use the full power of view caching, so it only accepts static (string) parameters,
and no variables or constants.

If you need a more flexible option, you can use the blade directive `@dynamicHeroIcon($name, $format, $options)`.
This directive will not cache the svg string, but will re-render the svg every time the view is rendered. With
this blade directive, you can use variables and constants.

```html
<div class="w-6">
	@dynamicHeroIcon($iconName, AntonioPrimera\HeroIcons\HeroIcon::FORMAT_SOLID)
</div>
<div class="w-6">
	@dynamicHeroIcon($iconName, $format, AntonioPrimera\HeroIcons\HeroIcon::SVG_REMOVE_SIZE)
</div>
<div class="w-6">
    <!-- You can also use the dynamic hero icon like the static one-->
    @dynamicHeroIcon('arrow-left')
</div>
```

### HeroIcon instance

All usage scenarios rely on an instance of **AntonioPrimera\HeroIcons\HeroIcon**.

```php
$icon = new \AntonioPrimera\HeroIcons\HeroIcon('arrow-down');
$iconHtmlString = $icon->render();
```

The constructor always requires the icon name, as found on the heroicons website. The name is transformed to
kebab-case, so you can use any format you like, as long as the kebab case of the name corresponds to an icon
in the icon set.

e.g. The following examples create identical icons:

```php
use AntonioPrimera\HeroIcons\HeroIcon;

$icon = new HeroIcon('arrow-down');
$icon = new HeroIcon('Arrow Down');
$icon = new HeroIcon('ArrowDown');
```

By default, if no format is specified, icons are created from the 'outline' icon subset. You can use one of the two
constants exposed by the HeroIcon class:

```php
use AntonioPrimera\HeroIcons\HeroIcon;

$icon = new HeroIcon('arrow-down', HeroIcon::FORMAT_OUTLINE);
$icon = new HeroIcon('arrow-down', HeroIcon::FORMAT_SOLID);
```

The third parameter of the constructor is a bitwise combination of options. At the moment the following options
are available:

```php
use AntonioPrimera\HeroIcons\HeroIcon;

$defaultOptions = HeroIcon::SVG_CURRENT_COLOR & HeroIcon::SVG_REMOVE_SIZE;
```

If SVG_CURRENT_COLOR is set, then the default stroke color of the icon is replaced with 'currentColor', so that the
icon's color will be the color set in the parent container.

If SVG_REMOVE_SIZE is set, then the default size of the icons (width and height, in pixels) is removed, so that the
icon can be resized using classes. In my opinion (this is purely subjective), the best way to use icons, is to set
its width to 100% of the parent container and to resize the parent container.

### Helper

A heroIcon helper function is also available.

Below you can see the function signature.

```php
function heroIcon(
	string $name,
	string $format = HeroIcon::FORMAT_OUTLINE,
	$classes = '',
	array $attributes = [],
	int $options = HeroIcon::SVG_REMOVE_SIZE | HeroIcon::SVG_CURRENT_COLOR
){ ... }
```

The helper function will return the rendered html of the svg icon, optionally applying one or more classes
(as a string or array) and a set of attributes.

### Methods

Once created, the icon instance can be configured, adding css classes on the outer svg node,
setting the size in pixels on the outer svg node, and setting attributes.

#### setClass(string | array $classes)

This method allows you to set one or more html classes on the outer svg node. The classes can be
provided as a string or as an indexed array.

#### setAttributes(array $attributes)

This method allows you to set one or more attributes on the outer svg node, provided an associative
array of attribute name => value pairs.

#### getSvgInstance()

The HeroIcon class uses the `meyfa/php-svg` package under the hood, and this method exposes
the SVG\SVG instance used by the HeroIcon instance to manipulate and render the svg.


## Future development

The package might use some more advanced features, like:

- caching icons (so that icons are reused)
- deleting / checking / retrieving attributes on the outer node
- manipulating the inner nodes (this can be done via the SVG instance - see method getSvgInstance)
- better testing
- using this package with other sets of icons (svg collection in a file / svg files) in a custom
location (in the laravel project importing this package)
- keeping the icon collection up to date (maybe creating a tool to automatically check / import the)
icon files from the heroicons git repository
- optimising the svg files, adding the currentColor option and removing the sizes in the files
directly - this will reduce the file sizes, and will not require doing this on each icon instantiation
(this is not a problem when using the @heroIcon blade directive, because the result is cached).

This package should remain very lightweight and fast. For advanced usage 