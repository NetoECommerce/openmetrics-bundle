# Symfony Openmetrics Bundle
Symfony bundle for exporting application telemetry from your application to Prometheus. 

### Installation

Download the bundle

```
composer require netolabs/openmetrics-bundle
```

Add the bundle to your AppKernel.php

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Neto\OpenmetricsBundle\NetoOpenmetricsBundle(),
        );
    }
}
```

Add the configuration to config.yml

```yaml
neto_openmetrics:
    namespace: neto_ecommerce
    ignored_routes: []
```

Add the routes.yml for your /metrics endpoint

```yaml
neto_openmetrics:
    resource: '@NetoOpenmetricsBundle/Resources/config/routes.yml'
    prefix: /
```