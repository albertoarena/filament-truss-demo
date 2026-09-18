# Filament Truss Demo

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/albertoarena/filament-truss/main/art/filamentphp/image-dark.jpg">
  <img src="https://raw.githubusercontent.com/albertoarena/filament-truss/main/art/filamentphp/image-light.jpg" alt="Filament Truss">
</picture>

A small Filament application for looking at
[albertoarena/filament-truss](https://github.com/albertoarena/filament-truss),
the plugin that renders a live ER diagram of the real database as a page inside
a Filament panel.

**This exists to be looked at.** The plugin's own test suite can tell you the
markup is right and the payload is embedded; it cannot tell you the diagram
looks like something a person would want to use. That needs a browser.

## The plugin comes from Packagist

This application installs `albertoarena/filament-truss` from Packagist like any
other host would, so `composer install` works anywhere and **this repository
stands alone**. What you see here is the released plugin, in a panel that
configures nothing special to get it.

Working on the plugin itself is the other direction, and it is documented there:
[`docs/MANUAL-TESTS.md`](https://github.com/albertoarena/filament-truss/blob/main/docs/MANUAL-TESTS.md)
covers pointing this application at a working tree and what to check once it is.

## Running it

```bash
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

## Why the panel says Marginalia Books

The repository is named after the plugin and **the panel deliberately is not**.
`AdminPanelProvider` sets no `->brandName()`, so the sidebar shows
`config('app.name')`, and that string is in frame in every screenshot that keeps
the panel chrome, including the ones on the documentation site. A panel named
after the plugin reads as a toy; one named after the shop it manages reads as an
application that happens to have the page installed, which is the whole claim
those pictures make.

So `APP_NAME` is a bookshop, in `.env.example` as well as in your `.env`, and
`tests/Feature/PanelBrandTest.php` fails if either goes back to naming the
plugin. Renaming it signs you out, because Laravel derives the session cookie
name from `APP_NAME`: visit `/demo-login` again.

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

**The bookshop is seeded with rows, and they are for the panel rather than for
the diagram.** An empty bookshop draws exactly the same diagram as a full one,
because the diagram is built from structure, and that has not changed: if this
application ever needs data to make the schema page look right, something has
gone wrong in the plugin. The rows are here so the two halves can be compared.
Browse the books, open the schema page, and find the same tables, columns and
keys read straight from the database.

Every relationship above has rows behind it, including the awkward ones: authors
with and without a mentor, and reviews with and without an account.

## What the panel manages, and what it does not

The resources are not a complete admin, and the gaps are the interesting part.

| Table | In the panel |
| --- | --- |
| `publishers`, `authors`, `books`, `reviews`, `tags` | A resource each |
| `users` | A resource, under Access |
| `author_book`, `taggables` | **No resource.** Pivots, managed through the relation managers on Book, Author and Tag, which is what a pivot deserves |
| `migrations`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `sessions`, `password_reset_tokens` | **Nothing.** Framework plumbing, and no panel manages it |

That last row is the part of the database an admin cannot see, which is what the
plugin's resource linking is meant to report, and the half that reports it is
still to come. Worth knowing before it is built: **Truss already excludes most of
those tables by config**, so the diagram draws eight, not sixteen. Excluded and
unmapped are different things, and a naive implementation would report the two
pivots as gaps when they are nothing of the sort.

## What to look at

- The page appears in the sidebar, and is hidden from anyone the Truss dashboard
  would hide it from.
- The diagram draws with **no request to any schema endpoint**. Open the network
  panel and confirm nothing hits `/truss/api/schema`: the payload is embedded in
  the page by `Truss::payload()`.
- Filter, focus and depth all work against the embedded payload, client side.
- The health markers come from `truss:doctor`, which rides the same payload.
- **Book and Author carry the focus button**, which is `HasViewInSchemaAction` on
  the resource and nothing else. It opens the diagram on that table, and it
  removes itself for a viewer who may not see the page or a table Truss
  excludes.

## Theming

The diagram takes the panel's own colours, from the custom properties Filament
generates out of the panel's colour configuration. Change the panel's primary
colour and the diagram follows it without the plugin knowing the colour's name.

Dark mode was expected to be the hard half and was not: Truss initialises Mermaid
with a neutral base theme and paints from CSS variables, so light and dark need no
re-render. All that was missing was that Filament says `dark` with a class and
Truss reads a `data-theme` attribute, which a few lines of script now mirror.

## License

MIT. The plugin and Laravel Truss are MIT too.
