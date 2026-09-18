<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * The panel is branded after the shop it manages, never after the plugin.
 *
 * `AdminPanelProvider` sets no `->brandName()`, so the sidebar renders
 * `config('app.name')`. The documentation screenshots keep the sidebar and the
 * topbar in frame on purpose: the claim they make is that the schema page is
 * native to the panel, and the chrome is the only evidence for it. A panel named
 * after the plugin reads as a toy rather than as an application that happens to
 * have the page installed, so the same image that proves the point undoes it.
 *
 * It was named after the plugin once and nothing could see it. Both halves are
 * checked because they fail differently: `config('app.name')` comes from an
 * untracked `.env` and is what the running panel puts on screen, while
 * `.env.example` is what a fresh clone starts from. Changing one and not the
 * other is the trap this exists to catch.
 */
class PanelBrandTest extends TestCase
{
    /**
     * Not a style preference. Each of these puts the plugin, the framework or
     * the word "demo" in the sidebar of every screenshot that keeps the chrome.
     */
    private const array FORBIDDEN = ['Truss', 'Filament', 'Demo'];

    public function test_the_running_panel_is_not_branded_after_the_plugin(): void
    {
        $this->assertBrand((string) config('app.name'), '.env');
    }

    public function test_a_fresh_clone_starts_from_the_same_brand(): void
    {
        $example = (string) file_get_contents(base_path('.env.example'));

        $this->assertSame(
            1,
            preg_match('/^APP_NAME="?([^"\n]+)"?$/m', $example, $matches),
            '.env.example has no APP_NAME, so a clone gets Laravel\'s default in the sidebar',
        );

        $this->assertBrand($matches[1], '.env.example');
    }

    private function assertBrand(string $name, string $source): void
    {
        $this->assertNotSame('', trim($name), "APP_NAME is empty in {$source}");

        foreach (self::FORBIDDEN as $word) {
            $this->assertStringNotContainsStringIgnoringCase(
                $word,
                $name,
                "APP_NAME in {$source} is \"{$name}\", which names the plugin rather than the shop",
            );
        }
    }
}
