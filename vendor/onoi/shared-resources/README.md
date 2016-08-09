# shared-resources

[![Latest Stable Version](https://poser.pugx.org/onoi/shared-resources/version.png)](https://packagist.org/packages/onoi/shared-resources)
[![Packagist download count](https://poser.pugx.org/onoi/shared-resources/d/total.png)](https://packagist.org/packages/onoi/shared-resources)
[![Dependency Status](https://www.versioneye.com/php/onoi:shared-resources/badge.png)](https://www.versioneye.com/php/onoi:shared-resources)

Some resources in this package were part of the [Semantic MediaWiki][smw] code base and are
now deployed as separate library so that [ResourceLoader][rl] modules can be used independently.

## Requirements

PHP 5.3 / HHVM 3.3 or later

## Installation

The recommended installation method for this library is to add
the dependency to your [composer.json][composer].

```json
{
	"require": {
		"onoi/shared-resources": "~0.2"
	}
}
```

## Usage

```
$GLOBALS['wgResourceModules']['ext.something'] = array(
	...
	'dependencies'  => array(
		'onoi.md5',
		'onoi.blobstore'
	);
);

mw.loader.using( 'onoi.md5' ).done( function () {
	// do something
} );
```

### Resources

- `onoi.md5` (1.1.0)
- `onoi.blockUI` (2.70)
- `onoi.rangeslider` (2.1.2)
- `onoi.localForage` (1.4.2)
- `onoi.async` (1.0)
- `onoi.qtip` (3.0.3)
- `onoi.jstorage` (0.4.12)
- `onoi.blobstore` (0.1)

## Contribution and support

If you want to contribute work to the project please subscribe to the
developers mailing list and have a look at the [contribution guidelinee](/CONTRIBUTING.md).
A list of people who have made contributions in the past can be found [here][contributors].

* [File an issue](https://github.com/onoi/shared-resources/issues)
* [Submit a pull request](https://github.com/onoi/shared-resources/pulls)

## Release notes

- 0.2 (2016-05-25)
 - Addedd `onoi.qtip` 3.0.3
 - Replaced `onoi.localForage` 1.4.0 with 1.4.2
 - Replaced `onoi.md5` 2.3.0 with 1.1.0 as some issues were encountered when loading it as resource

- 0.1 (2016-04-05)
 - Initial release

## License

[GNU General Public License 2.0 or later][license]. Libraries and third-party
plug-ins are deployed with their respective published licenses.

- [MD5](https://github.com/blueimp/JavaScript-MD5), MIT license
- [jquery.blockUI](http://malsup.com/jquery/block/), Dual licensed under the MIT and GPL licenses
- [ion.rangeSlider](https://github.com/IonDen/ion.rangeSlider), MIT license
- [Mozilla localForage](https://github.com/mozilla/localForage/releases), Apache License 2.0
- [jquery.async](http://mess.genezys.net/jquery/jquery.async.php) Dual licensed under the MIT and GPL licenses
- [jStorage](https://github.com/andris9/jStorage), Unlicense
- [jquery.qtip](http://qtip2.com/), Dual licensed under the MIT and GPL licenses
- `onoi.blobstore` (0.1, GPL 2+)

[composer]: https://getcomposer.org/
[contributors]: https://github.com/onoi/shared-resources/graphs/contributors
[license]: https://www.gnu.org/copyleft/gpl.html
[travis]: https://travis-ci.org/onoi/shared-resources
[smw]: https://github.com/SemanticMediaWiki/SemanticMediaWiki/
[rl]: https://www.mediawiki.org/wiki/ResourceLoader
