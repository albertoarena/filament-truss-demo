<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * A smoke test over the panel.
 *
 * This application exists to be looked at, so its suite is deliberately thin.
 * What it does earn is a check that every page still renders: a resource can be
 * broken by an upgrade of Filament or of the plugin without anything else
 * noticing, and finding out by opening a browser is how a broken demo gets
 * screenshotted.
 */
class PanelPagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The panel is open in `local` and closed everywhere else, because the demo
     * user model does not implement `FilamentUser`. The suite runs in `testing`,
     * so without this every page here answers 403 and the test would be checking
     * the environment rather than the pages.
     *
     * Both the container's environment and the config value, because the two are
     * read by different guards: Filament's panel middleware checks
     * `config('app.env')`, while the schema page's own check goes through the
     * container. Setting one and not the other passes seven of these and fails
     * the eighth.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->detectEnvironment(fn (): string => 'local');
        config(['app.env' => 'local']);

        // Truss defaults to enabled in `local` only, decided when config loads,
        // which is `testing` here. The schema page checks that switch before
        // anything else, so without this it hides itself and the test below
        // would be measuring the kill switch rather than the page.
        config(['truss.enabled' => true]);
    }

    #[DataProvider('pages')]
    public function test_a_panel_page_renders(string $url): void
    {
        $this->actingAs(User::factory()->create());

        $this->get($url)->assertOk();
    }

    public static function pages(): array
    {
        return [
            'dashboard' => ['/admin'],
            'books' => ['/admin/books'],
            'authors' => ['/admin/authors'],
            'publishers' => ['/admin/publishers'],
            'reviews' => ['/admin/reviews'],
            'tags' => ['/admin/tags'],
            'users' => ['/admin/users'],
            // The page this whole application is here to look at.
            'schema' => ['/admin/database-schema'],
        ];
    }

    public function test_the_schema_page_goes_when_truss_is_switched_off(): void
    {
        // The other half of the switch above, and the reason the plugin checks
        // it rather than only asking the gate: an operator who turns Truss off
        // expects it off everywhere, including inside a panel.
        config(['truss.enabled' => false]);

        $this->actingAs(User::factory()->create());

        $this->get('/admin/database-schema')->assertForbidden();

        // The rest of the panel is unaffected, which is the point of it being
        // this page's own question rather than the panel's.
        $this->get('/admin/books')->assertOk();
    }

    public function test_a_record_page_renders_with_its_relation_managers(): void
    {
        $this->actingAs(User::factory()->create());

        $book = Book::factory()->create();

        $this->get('/admin/books/'.$book->id)->assertOk();
    }
}
