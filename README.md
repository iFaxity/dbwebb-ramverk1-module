faxity/ramverk1-module
======================

[![Build Status](https://travis-ci.com/iFaxity/dbwebb-ramverk1-module.svg?branch=master)](https://travis-ci.com/iFaxity/dbwebb-ramverk1-module)
[![Build Status](https://scrutinizer-ci.com/g/iFaxity/dbwebb-ramverk1-module/badges/build.png?b=master)](https://scrutinizer-ci.com/g/iFaxity/dbwebb-ramverk1-module/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/iFaxity/dbwebb-ramverk1-module/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/iFaxity/dbwebb-ramverk1-module/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/iFaxity/dbwebb-ramverk1-module/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/iFaxity/dbwebb-ramverk1-module/?branch=master)

This is an extension for Anax to use IP validation and Weather forecasts.

## Installation

To install the package using composer:

`composer require faxity/ramverk1-module`

Then after that you need to copy over the configuration and view templates. One way to do it is using rsync:

```bash
rsync -av vendor/faxity/ramverk1-module/config/ config/
rsync -av vendor/faxity/ramverk1-module/view/ view/
```
