faxity/ramverk1-module
======================

This is an extension for Anax to use IP validation and Weather forecasts.

## Installation

To install the package using composer:

`composer require faxity/ramverk1-module`

Then after that you need to copy over the configuration and view templates. One way to do it is using rsync:

```bash
rsync -av vendor/faxity/ramverk1-module/config config/
rsync -av vendor/faxity/ramverk1-module/view view/
```
