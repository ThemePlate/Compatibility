# ThemePlate Compatibility

## Usage

```php
use ThemePlate\Compatibility;

$compatibility = new Compatibility( 'My Project', '6.0', '7.4' );

add_action( 'admin_notice', array( $compatibility, 'maybe_notice' ) );
```
