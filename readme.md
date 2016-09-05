# Asset Loader

Public asset files loader.

## Installation

`composer require heavenproject/asset-loader`

## How It Works

Asset Loader is [Latte macro](http://latte.nette.org/en/).

After the asset file is loaded in the Asset Loader, it reads the asset url,
saves this information in temporary file and appends a version number
after this url in a form of query string,
e.g. `http://example.com/images/image.jpg?v=1`. After the asset file has been
modified, Asset Loader registers this during a new request and increases
version number in url query string,
e.g. `http://example.com/images/image.jpg?v=2`. This will force the web browser
to download a new version of this asset file from server instead of using
it from browser cache.

> **NOTE:** Asset files can be loaded either just by specifiyng its absolute filepath
from the website document root directory,
e.g. `/images/image.jpg` or by using its external url,
e.g. `http://example.com/images/image.jpg`, but if using the later form (external url), keep in mind,
that trying to work with assets from different domains will not work, because in this case Asset Loader
checks the domain in url and if it does not match the website url, it will
only return the original url that was given to it, so browser can work with the given resource as usual.
So please only load your website local assets.

## Requirements

- [Nette Framework](https://github.com/nette/nette)

## Documentation

In order to use Asset Loader you must register it as extension in configuration file:

```neon
extensions:
    asset: HeavenProject\AssetLoader\AssetLoaderExtension
```

Then you must configure it:

```neon
asset:
    publicDir: %wwwDir%
    targetDir: %tempDir%/assets
```

After this you are ready to use the Asset Loader:

```latte
<img src="{asset $basePath . '/images/image.jpg'}" alt="Image">
```

## License

This source code is [free software](http://www.gnu.org/philosophy/free-sw.html)
available under the [MIT license](license.md).
