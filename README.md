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

$compatibility->message_header( 'Sorry! Plugin is not compatible.' );
$compatibility->message_wp( 'Requires WP 5.0 or higher' );
$compatibility->message_php( 'Requires PHP 5.6 or higher' );
```
