# Filament Truss Demo

A small Filament application for looking at
[albertoarena/filament-truss](https://github.com/albertoarena/filament-truss),
the plugin that renders a live ER diagram of the real database as a page inside
a Filament panel.

**This exists to be looked at.** The plugin's own test suite can tell you the
markup is right and the payload is embedded; it cannot tell you the diagram
looks like something a person would want to use. That needs a browser.

## Status: private, and tracking an unreleased plugin

The plugin has no tagged release, so this application does **not** install it
from Packagist. It uses a Composer **path repository** pointing at a sibling
checkout, symlinked, which means the app runs whatever is in your working tree.
Edit the plugin, reload the page, see the change.

That is deliberate and it is the whole point, but it has one consequence: **this
repository does not stand alone.** Without `../filament-truss` beside it,
`composer install` fails.

## Running it

```bash
git clone git@github.com:albertoarena/filament-truss.git      # the plugin, as a sibling
git clone git@github.com:albertoarena/filament-truss-demo.git
cd filament-truss-demo

composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed

php artisan serve
```

Then open **`/demo-login`**, which signs you in as the seeded user and drops you
straight on the schema page. It is guarded to the `local` environment and 404s
anywhere else. There is nothing here worth protecting: every row is fixture data
and the schema is the product.

The panel is at `/admin` and the page at `/admin/database-schema`.

## What the schema is, and why

Laravel's own tables are four boxes with almost no edges, which tells you nothing
about whether the diagram works. `database/migrations` adds a small bookshop
chosen to exercise the things that actually break:

- **one to many**, publishers to books
- **many to many**, authors and books through a real pivot with a composite
  unique key
- **a self-referencing key**, `authors.mentor_id`, which Truss marks on the
  column rather than drawing as a loop
- **a nullable key**, `reviews.user_id`, with `ON DELETE SET NULL`
- **a polymorphic pair**, `taggables`, which has no single table to point at
- **an enum**, `books.status`, whose values are clickable in the diagram

**No rows are seeded beyond one user.** An empty bookshop draws exactly the same
diagram as a full one, because the diagram is built from structure. If this
application ever needs data to make the schema page look right, something has
gone wrong in the plugin.

## What to look at

- The page appears in the sidebar, and is hidden from anyone the Truss dashboard
  would hide it from.
- The diagram draws with **no request to any schema endpoint**. Open the network
  panel and confirm nothing hits `/truss/api/schema`: the payload is embedded in
  the page by `Truss::payload()`.
- Filter, focus and depth all work against the embedded payload, client side.
- The health markers come from `truss:doctor`, which rides the same payload.

## Known rough edges

**Theming is not done.** The plugin currently loads Truss's own stylesheet, so
the diagram brings its blueprint look into the panel instead of taking the
panel's colours, and the grid background escapes the page container. Consuming
the panel's own CSS custom properties is planned work, not an oversight, and it
is written up in the plugin's `docs/DESIGN.md`.

**Dark mode will look wrong** for the same reason, and for a second one: Filament
toggles dark mode client side with no page load, while Mermaid takes its theme at
render time, so the diagram has to be re-rendered on the toggle rather than
merely restyled.

## License

MIT. The plugin and Laravel Truss are MIT too.
