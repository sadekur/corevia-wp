# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A WordPress plugin (`corevia-wp`, PHP namespace `CoreviaWP`) with a PHP backend and a React (JSX) admin SPA. The plugin was originally scaffolded from a boilerplate called "ThrailWP" — most references were renamed to `coreviawp`/`CoreviaWP`, but a few artifacts (e.g. `readme.md`'s `wp thrailwp ...` CLI examples, some docblock `@package ThrailWP` tags) are stale leftovers with no corresponding implementation in this repo; don't trust them as working commands.

## Commands

```bash
composer update          # install PHP dependencies (PSR-4 autoload: CoreviaWP\ -> app/)
npm install               # install JS dependencies
npm run build              # webpack build (admin + public SPA bundles, tailwind) -> spa/build/
npm run watch               # webpack --watch
npm run start                 # webpack-dev-server (references webpack.config.dev.js, which does not exist in this repo — will fail until added)
```

`npm run build:admin` / `npm run build:public` reference `webpack.config.admin.js` / `webpack.config.public.js`, which also don't exist — only `webpack.config.js` (building both `admin` and `public` entries plus `tailwind` in one pass) is present and working.

### Tests

- PHP: PHPUnit 10.5, config in `phpunit.xml`, suite root `tests/PHP`. Bootstrap (`tests/bootstrap.php`) loads a live WordPress install via relative path `../../../wp-load.php`, so tests must be run from within an actual WP install (this plugin sits in `wp-content/plugins/corevia-wp`), not standalone. Run with `vendor/bin/phpunit`.
  - Note: `tests/PHP/Controller/FrontTest.php` references a namespace/class (`CoreviaWP\Controller\Front`) that no longer matches the current code (`CoreviaWP\Controllers\Front\Debug`) — it is stale and will not pass as-is.
- JS: `tests/JS/ExampleTest.js` exists but no test runner is configured in `package.json` (no jest/mocha script).
- PHPCS/WPCS and PHPCompatibility are dev dependencies (`composer.json`) but no `phpcs.xml` ruleset is present yet.

## Architecture

### Bootstrap flow

`corevia-wp.php` is the plugin entry point. It defines constants (`COREVIAWP_FILE`, `COREVIAWP_VERSION`, `COREVIAWP_PLUGIN_DIR`, `COREVIAWP_PLUGIN_URL`, `COREVIAWP_ASSETS_URL`), requires `vendor/autoload.php`, and wires WP lifecycle hooks to classes in `app/Core`:

- `register_activation_hook` → `Core\Installer::install()` — creates DB tables via `Models\Database` (idempotent, gated on an options-stored `corevia-wp_db_version`).
- `register_deactivation_hook` → `Core\Deactivator::deactivate()`.
- `plugins_loaded` → `Core\Activator::activate()`.
- `Core\Initializer` is a singleton (`get_instance()`) instantiated at the bottom of the entry file; its `init()` fires `coreviawp_before_initialize` / `coreviawp_after_initialize` action hooks and loads controllers.

`app/Config/autoload.php` (loaded as a composer `files` autoload entry) auto-`require_once`s every `*.php` in `app/Config/` except itself — drop config files there and they load automatically, no registration needed.

### Controller auto-discovery (convention over registration)

`Core\Initializer::init_controllers()` does NOT use an explicit registry. It `glob()`s three directories and instantiates every class whose filename matches the directory's namespace, if the class exists:

- `app/Controllers/Admin/*.php` → `CoreviaWP\Controllers\Admin\{Filename}` — loaded only when `is_admin()`.
- `app/Controllers/Front/*.php` → `CoreviaWP\Controllers\Front\{Filename}` — loaded only when NOT `is_admin()`.
- `app/Controllers/Common/*.php` → `CoreviaWP\Controllers\Common\{Filename}` — always loaded.

**To add a controller: create the file in the right directory with a class name matching the filename, and it is picked up automatically** (also filterable via `coreviawp_admin_controllers` / `coreviawp_frontend_controllers` / `coreviawp_common_controllers`). Controllers do their work in `__construct()` by wiring WP hooks — there is no central router.

### Traits provide the object toolkit

Controllers/models compose behavior via traits in `app/Traits/` rather than base classes:

- `Hook` — `action()`/`filter()`/`shortcode()`/`ajax()` wrappers around WP's hook API (each checks `is_callable()` before registering).
- `Asset` — script/style enqueue + localize helpers.
- `Menu` — `add_menu()`/`add_submenu()`; also records a global `$coreviawp_menus` array (consumed by the admin JS bundle via `wp_localize_script`, see `Controllers/Admin/Init.php`).
- `Rest` — `register_route()` (namespace fixed to `cx/v1` via the `$namespace` property) plus `response_success()`/`response_error()`/`response()` JSON helpers. Note `Controllers/Common/API.php` calls `register_rest_route()` directly instead of using this trait's `register_route()` — both patterns exist in the codebase.
- `Auth`, `Cache`, `Cleaner`, `Limiter`, `Queue`, `Request` — other cross-cutting helpers, check each before assuming behavior.

### Data layer

`Models\Database` is a generic per-table query builder, not an ORM — instantiate with a bare table name (`new Database('contacts')`); it prefixes with `$wpdb->prefix . 'coreviawp_'` automatically. Provides `create_table()` (dbDelta-based, supports unique keys/indexes/foreign keys), `get_rows()` (with a small condition DSL: plain `['col' => val]`, operator `['col' => ['>', val]]`, and `['col' => ['IN', [...]]]`), plus standard `insert_row`/`update_row`/`delete_row`/`get_by_id`/`get_count` and batch variants. `app/Interfaces/Entity.php` defines a `create/update/delete/get/list` contract for entities but isn't yet implemented by any class — check before assuming a model implements it.

`app/Abstracts/` (`Field`, `Meta`, `User`) hold base classes for extension; `app/Helpers/Field/*` are per-input-type field renderers (Text, Select, Color, WYSIWYG, etc.) used by the settings UI.

`app/API/Option.php` backs the `cx/v1/option` REST endpoints (GET/POST/DELETE, admin-only via `is_admin` permission check) registered in `Controllers/Common/API.php`.

### Frontend (SPA)

Two independent React entry points built by webpack into `spa/build/`:

- `spa/admin/src/App.jsx` → `admin.bundle.js`, mounted into `#corevia-wp_render` (added by `Controllers/Admin/Menu.php::callback_main_menu`). Hash-based routing (`#/home`, `#/help`, `#/settings`) driven by manual `window.location.hash` listening — no router library despite `react-router-dom` being a dependency.
- `spa/public/src/App.jsx` → `public.bundle.js`, for frontend-facing UI.
- `tailwind` is built as its own webpack entry (`assets/common/css/tailwind.css` → `tailwind.bundle.js`) and enqueued as a script, not a stylesheet — see `Controllers/Common/Init.php::add_assets()`.

Non-SPA static assets (plain enqueued JS/CSS, not built by webpack) live under `assets/admin`, `assets/common`, `assets/public`.

### Templates

`Helpers\Utility::get_template( $template, $args )` renders PHP files from `views/` (extension optional, `.php` assumed), extracting `$args` into scoped variables. Views are organized as `views/settings/`, `views/shortcodes/`, `views/templates/`.

### Release process

`.github/workflows/wp.org.yml` pushes to WordPress.org SVN on push to the `wp.org` branch, using `VERSION`/`SLUG` hardcoded in the workflow file (currently `0.9` / `corevia-wp`) — bump both the plugin header version and this workflow value together when releasing.
