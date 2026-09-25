# ModHub — Abasthan edition

No MySQL required.

## Abasthan environment variables

Set:
- `MODHUB_ADMIN_USERNAME`
- `MODHUB_ADMIN_PASSWORD_HASH`

Generate a password hash with:
`php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"`

## Important storage note

This version stores metadata in `data/mods.json` and uploaded files in `uploads/`.
Those locations MUST be persistent on your Abasthan app. If the platform's PHP filesystem is ephemeral, use its persistent-volume/storage feature or an external object/file store before accepting real uploads.

## API
GET `/api/mods.php`
GET `/api/mod.php?id=1`
GET `/api/download.php?id=1`
