# Deploying MBSC Firm

What the server needs to run this application. Nothing here has been deployed or tested on a production host.

## Server requirements

| Component | Requirement |
| --- | --- |
| PHP | 8.2 or newer (developed on 8.4), with the usual Laravel extensions: `mbstring`, `pdo_mysql`, `openssl`, `tokenizer`, `xml`, `ctype`, `fileinfo`, `bcmath`, `curl` |
| Composer | 2.x |
| MySQL | 8.x or MariaDB 10.6+. Sessions, cache and the queue also use the database |
| Node.js | 22 (LTS). Needed on the server at runtime for the SSR process, and wherever assets are built |
| Web server | Nginx or Apache pointing at `public/`, with HTTPS |
| Process manager | Supervisor or systemd, to keep the queue worker and the SSR process running |

## Environment variables

Copy `.env.example` to `.env` and set at least these:

| Variable | Value |
| --- | --- |
| `APP_NAME` | `"MBSC Firm"`. Used in page titles and emails |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://www.mbscfirm.com`. Used for canonical URLs, the sitemap and links in emails |
| `APP_KEY` | Generate with `php artisan key:generate` |
| `APP_SUPER_ADMIN_EMAIL` | Email of the account that bypasses all permission checks |
| `APP_DEFAULT_USER_PASSWORD` | Change from `password` before seeding, then change the admin's password after first sign-in |
| `DB_*` | Database host, name, user and password |
| `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_SCHEME` | SMTP details for enquiry notifications. The default `log` mailer sends nothing |
| `MAIL_FROM_ADDRESS` | An address on a domain the SMTP service is allowed to send from |
| `QUEUE_CONNECTION` | `database` |
| `INERTIA_SSR_ENABLED` | `true` |

The address that receives enquiry emails is not an environment variable. Set it in the admin panel under Site Settings, "Enquiry notification email".

## First deployment

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build:ssr          # builds public/build and bootstrap/ssr
php artisan migrate --force
php artisan db:seed --force  # permissions, site settings, services, founder, FAQs
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

`storage/` and `bootstrap/cache/` must be writable by the PHP user.

After seeding, sign in, change the admin password, and check Admin > Site Settings.

## Long-running processes

Both must be kept alive by Supervisor or systemd and restarted on each deploy.

### Queue worker

Enquiry notification emails are queued. Without a worker, enquiries are still saved and visible in the admin panel, but no email is sent.

```bash
php artisan queue:work --tries=3 --max-time=3600
```

Restart after a deploy with `php artisan queue:restart`.

### SSR process

Renders the public pages to HTML so search engines and link previews see real content.

```bash
php artisan inertia:start-ssr
```

It listens on `127.0.0.1:13714`; do not expose that port. If it is down, public pages still work but are rendered in the browser, and titles, descriptions and social tags are still served because they come from the PHP template.

Restart it after every `npm run build:ssr`, because it loads the bundle once at start:

```bash
php artisan inertia:stop-ssr   # the process manager then starts it again
```

## Cron

The application has no scheduled tasks at present, so no cron entry is required. If tasks are added later, the standard Laravel entry is:

```
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

## Each later deploy

```bash
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build:ssr
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan queue:restart
php artisan inertia:stop-ssr
```

Do not run `db:seed` again as a routine step. The service, FAQ and team seeders overwrite rows with the same slug, question or name, which would undo edits made in the admin panel. Site settings are safe: that seeder only fills in settings that have never been saved.

## Web server settings

- Serve `public/build/assets/*` with `Cache-Control: public, max-age=31536000, immutable`. File names are content-hashed.
- Enable gzip or Brotli for HTML, CSS, JavaScript and SVG. Local measurements were taken without compression, and the JavaScript shrinks to roughly a third of its size with it.
- Redirect HTTP to HTTPS and pick one host name (`www` or bare) as canonical.
- Uploaded images are stored in `storage/app/public` and served through the `public/storage` link.

## After going live

- Submit `https://www.mbscfirm.com/sitemap.xml` in Google Search Console.
- Send a test enquiry from the contact page and confirm it appears under Admin > Enquiries and arrives by email.
- Check a link preview by pasting the home page URL into WhatsApp.
