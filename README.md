# ThemePlate Compatibility

## Usage

```php
use ThemePlate\Compatibility;

$compatibility = new Compatibility( 'My Project', '6.0', '7.4' );

add_action( 'admin_notice', array( $compatibility, 'maybe_notice' ) );
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
