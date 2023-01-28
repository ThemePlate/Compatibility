# ThemePlate Compatibility

## Usage

```php
use ThemePlate\Compatibility;

( new Compatibility( 'My Project', '6.0', '8.0' ) )->setup();
```

### Custom messages

```php
use ThemePlate\Compatibility;

$compatibility = new Compatibility( 'Plugin', '5.0', '5.6' );

/* translators: %s package name */
$compatibility->message_header( 'Sorry! %s is not compatible.' );
/* translators: 1. required version, 2. installed version */
$compatibility->message_wp( __( 'Requires WP %1$s or higher but currently running at %2$s', 'custom_domain' ) );
/* translators: 1. required version, 2. installed version */
$compatibility->message_php( __( 'Requires PHP %1$s or higher but currently running at %2$s', 'custom_domain' ) );
```
