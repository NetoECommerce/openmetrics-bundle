# Symfony Openmetrics Bundle
Symfony bundle for exporting application telemetry from your application to Prometheus. 

### Installation

Download the bundle

```
composer require netolabs/openmetrics-bundle
```

Add the bundle to your AppKernel

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