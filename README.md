# Drupal Module Generator (dmg)

A scaffolding tool for generating new modules for Drupal 7 and (soon) 8.

[Watch a short demo][demo].

[demo]: https://opdavi.es/6i3YZ 'A short demo video on YouTube'

## Installation

The Drupal Module Generator is installed via [Composer][]:

```bash
composer global require opdavies/drupal-module-generator
```

[composer]: https://getcomposer.org

## Usage

### Drupal 7

```bash
dmg generate:drupal-7-module {name}
```

Generated Drupal 7 modules contain the appropriately named `.info` and `.module` files,
as well as a test case located in `src/Tests/Functional` which [is loaded automatically](https://www.oliverdavies.uk/articles/psr4-autoloading-test-cases-drupal-7).
