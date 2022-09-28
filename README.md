# yoshz/esi-debug-bundle

A Symfony bundle that displays debug information for ESI requests.

Inspired by [nelmio/NelmioEsiDebugBundle](https://github.com/nelmio/NelmioEsiDebugBundle)

## Installation

1. Add `yoshz/esi-debug-bundle` to your `composer.json`:

    ```bash
    composer require --dev yoshz/esi-debug-bundle
    ```

2. Add the bundle to `config/bundles.php`:

    ```php
    return [
        ...
        Yoshz\EsiDebugBundle\YoshzEsiDebugBundle::class => ['dev' => true],
    ];
    ```
