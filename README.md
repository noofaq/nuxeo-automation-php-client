
[![Build Status](https://qa.nuxeo.org/jenkins/buildStatus/icon?job=Client/nuxeo-automation-php-client/master)](https://qa.nuxeo.org/jenkins/job/Client/job/nuxeo-automation-php-client/job/master/)

# Nuxeo Automation PHP Client

The Nuxeo Automation PHP Client is a PHP client library for Nuxeo Automation API.

This is supported by Nuxeo and compatible with Nuxeo LTS 2015 and latest Fast Tracks.

# Code

## Requirements

 * PHP >= 5.3.3
 * [Composer](https://getcomposer.org/)

## Getting Started

### Server

- [Download a Nuxeo server](http://www.nuxeo.com/en/downloads) (the zip version)

- Unzip it

- Linux/Mac:
    - `NUXEO_HOME/bin/nuxeoctl start`
- Windows:
    - `NUXEO_HOME\bin\nuxeoctl.bat start`

- From your browser, go to `http://localhost:8080/nuxeo`

- Follow Nuxeo Wizard by clicking 'Next' buttons, re-start once completed

- Check Nuxeo correctly re-started `http://localhost:8080/nuxeo`
  - username: Administrator
  - password: Administrator

### Library import

Download the latest build [Nuxeo Automation PHP Client master](https://github.com/nuxeo/nuxeo-automation-php-client/archive/master.zip).
Download the latest stable release [Nuxeo Automation PHP Client 1.5.0](https://github.com/nuxeo/nuxeo-automation-php-client/archive/1.5.0.tar.gz).

Composer:

```
  "require": {
    "nuxeo/nuxeo-automation-php-client": "~1.5.0"
  }
```

### Usage

#### Creating a Client

The following documentation and samples applies for the 1.5 and newer versions. Calls to the Automation API for previous versions of the client will require adjustments.

For a given `url`:

```php
$url = 'http://localhost:8080/nuxeo';
```

And given credentials:

```php
use Nuxeo\Client\Api\NuxeoClient;

$client = new NuxeoClient($url, 'Administrator', 'Administrator');
```

Options:

```php
// For defining all schemas
$client = $client->schemas("*");
```

```php
// For changing authentication method

use Nuxeo\Client\Api\Auth\PortalSSOAuthentication;
use Nuxeo\Client\Api\Auth\TokenAuthentication;

// PortalSSOAuthentication with nuxeo-platform-login-portal-sso
$client = $client->setAuthenticationMethod(new PortalSSOAuthentication($secret, $username));

// TokenAuthentication
$client = $client->setAuthenticationMethod(new TokenAuthentication($token));
```

#### APIs

##### Automation API

To use the Automation API, `Nuxeo\Client\Api\NuxeoClient#automation()` is the entry point for all calls:

```php
use Nuxeo\Client\Api\Objects\Document;

// Fetch the root document
$result = $client->automation('Repository.GetDocument')->param("value", "/")->execute(Document::className);
```

```php
use Nuxeo\Client\Api\Objects\Documents;

// Execute query
$operation = $client->automation('Repository.Query')->param('query', 'SELECT * FROM Document');
$result = $operation->execute(Documents::className);
```

```php
use Nuxeo\Client\Api\Objects\Blob\Blob;
use Nuxeo\Client\Api\Objects\Blob\Blobs;

// To upload|download blob(s)

$fileBlob = Blob::fromFile('/local/file.txt', 'text/plain');
$blob = $client->automation('Blob.AttachOnDocument')->param('document', '/folder/file')->input($fileBlob)->execute(Blob::className);

$inputBlobs = new Blobs();
$inputBlobs->add('/local/file1.txt', 'text/plain');
$inputBlobs->add('/local/file2.txt', 'text/plain');
$blobs = $client->automation('Blob.AttachOnDocument')->param('xpath', 'files:files')->param('document', '/folder/file')->input($inputBlobs)->execute(Blobs::className);

$resultBlob = $client->automation('Document.GetBlob')->input('folder/file')->execute(Blob::className);
```

#### Errors/Exceptions

The main exception type is `Nuxeo\Client\Internals\Spi\NuxeoClientException` and contains:

- The HTTP error status code (666 for internal errors)

- An info message

## Docker

We provide a [docker-compose.yml](https://github.com/nuxeo/nuxeo-automation-php-client/blob/master/docker-compose.yml) for quick testing

Just install docker-compose and run `docker-compose up`, you'll have a nuxeo running on http://localhost:9081/ and nginx on http://localhost:9080/

You can access the samples with http://localhost:9080/samples/B1.php for example.

# Contributing / Reporting issues

We are glad to welcome new developers, and even simple usage feedback is great

 * Ask your questions on http://answers.nuxeo.com/
 * Report issues on this GitHub repository (see [issues link](https://github.com/nuxeo/nuxeo-automation-php-client/issues) on the right)

# License

[Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0.html)

# About Nuxeo

The [Nuxeo Platform](http://www.nuxeo.com/products/content-management-platform/) is an open source customizable and extensible content management platform for building business applications. It provides the foundation for developing [document management](http://www.nuxeo.com/solutions/document-management/), [digital asset management](http://www.nuxeo.com/solutions/digital-asset-management/), [case management application](http://www.nuxeo.com/solutions/case-management/) and [knowledge management](http://www.nuxeo.com/solutions/advanced-knowledge-base/). You can easily add features using ready-to-use addons or by extending the platform using its extension point system.

The Nuxeo Platform is developed and supported by Nuxeo, with contributions from the community.

Nuxeo dramatically improves how content-based applications are built, managed and deployed, making customers more agile, innovative and successful. Nuxeo provides a next generation, enterprise ready platform for building traditional and cutting-edge content oriented applications. Combining a powerful application development environment with
SaaS-based tools and a modular architecture, the Nuxeo Platform and Products provide clear business value to some of the most recognizable brands including Verizon, Electronic Arts, Sharp, FICO, the U.S. Navy, and Boeing. Nuxeo is headquartered in New York and Paris.
More information is available at [www.nuxeo.com](http://www.nuxeo.com).
