# remi

[![Build Status](https://secure.travis-ci.org/onoi/remi.svg?branch=master)](http://travis-ci.org/onoi/remi)
[![Code Coverage](https://scrutinizer-ci.com/g/onoi/remi/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/onoi/remi/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/onoi/remi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/onoi/remi/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/onoi/remi/version.png)](https://packagist.org/packages/onoi/remi)
[![Packagist download count](https://poser.pugx.org/onoi/remi/d/total.png)](https://packagist.org/packages/onoi/remi)
[![Dependency Status](https://www.versioneye.com/php/onoi:remi/badge.png)](https://www.versioneye.com/php/onoi:remi)

This library is intended to generate a filtered record from a REST/Http metadata provider response
(to mine a REST response a.k.a. `remi`). The code base was part of [Semantic Cite][scite] and
is now being deployed as independent library. Supported providers are:

- CrossRef (DOI)
- VIAF
- PubMed (PMID and PMCID)
- OCLC (WorldCat)
- OpenLibrary (OLID, ISBN)

## Requirements

PHP 5.3 / HHVM 3.5 or later

## Installation

The recommended installation method for this library is to add the dependency to your [composer.json][composer].

```json
{
	"require": {
		"onoi/remi": "~0.2"
	}
}
```

## Usage

```php
use Onoi\HttpRequest\HttpRequestFactory;
use Onoi\Remi\FilteredHttpResponseParserFactory;

$httpRequestFactory = new HttpRequestFactory()

$filteredHttpResponseParserFactory = new FilteredHttpResponseParserFactory(
	$httpRequestFactory->newCurlRequest()
);

$crossRefFilteredHttpResponseParser = $filteredHttpResponseParserFactory->newCrossRefFilteredHttpResponseParser(
	new FilteredRecord()
)

$crossRefFilteredHttpResponseParser->doFilterResponseFor( '10.1126/science.1152662' );

$filteredRecord = new FilteredRecord();
$filteredRecord->setRedactedFields( array( 'pages', 'abstract' ) );

$pubMedFilteredHttpResponseParser = $filteredHttpResponseParserFactory->newNcbiPubMedFilteredHttpResponseParser(
	$filteredRecord
)

$pubMedFilteredHttpResponseParser->doFilterResponseFor( '19782018' );
```
The `FilteredHttpResponseParser` (implementing the `ResponseParser` interface) returns a
simple `array` filtered from a REST response.

`FilteredHttpResponseParser::doFilterResponseFor` is not expected to make any input validation (in terms of
format or range) for the requested response therefore the implementing class is responsible for an appropriate
validation process.

`FilteredRecord::setRedactedFields` can be used to remove selected fields from the record.

It is further possible to invoke a `CachedCurlRequest` to avoid repeated requests to the same REST API url.

## Contribution and support

If you want to contribute work to the project please subscribe to the
developers mailing list and have a look at the [contribution guidelinee](/CONTRIBUTING.md). A list of people who have made contributions in the past can be found [here][contributors].

* [File an issue](https://github.com/onoi/remi/issues)
* [Submit a pull request](https://github.com/onoi/remi/pulls)

### Tests

The library provides unit tests that covers the core-functionality normally run by the [continues integration platform][travis]. Tests can also be executed manually using the `composer phpunit` command from the root directory.

### Release notes

- 0.2 (2015-09-25)
 - Changed `ResponseParser` interface to clarify method names
- 0.1 (2015-08-03) Initial release
 - Added `ResponseParser` interface
 - Added `FilteredHttpResponseParserFactory` to provide access to CrossRef, VIAF, PubMed, OCLC, and OpenLibrary REST API

## License

[GNU General Public License 2.0 or later][license].

[composer]: https://getcomposer.org/
[contributors]: https://github.com/onoi/remi/graphs/contributors
[license]: https://www.gnu.org/copyleft/gpl.html
[travis]: https://travis-ci.org/onoi/remi
[scite]: https://github.com/SemanticMediaWiki/SemanticCite/
