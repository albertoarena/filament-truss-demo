@php
    use AlbertoArena\FilamentTruss\Pages\SchemaPage;
    use Composer\InstalledVersions;
    use Filament\Support\Icons\Heroicon;

    $truss = InstalledVersions::getPrettyVersion('albertoarena/laravel-truss');
    $plugin = InstalledVersions::getPrettyVersion('albertoarena/filament-truss');
@endphp

{{--
    A mirror of Filament's own info widget, for the package this panel exists to
    show. Same shape on purpose: brand and version on the left, links on the
    right, so the two cards read as a pair rather than as a card and a banner.

    Laid out with inline styles rather than utility classes, because this
    application has no Tailwind build of its own and a class that is not in the
    panel's compiled stylesheet would simply do nothing.
--}}
<x-filament-widgets::widget>
    <x-filament::section>
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
            <div>
                <a
                    href="https://trussphp.com"
                    rel="noopener noreferrer"
                    target="_blank"
                    style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 1.5rem; font-weight: 600; letter-spacing: -0.01em; color: var(--gray-950);"
                >
                    {{-- Truss's own mark, from its dashboard. --}}
                    <svg width="28" height="28" viewBox="0 0 32 32" fill="none" stroke="currentColor" aria-hidden="true">
                        <path d="M16 5 L27 26 H5 Z" stroke-width="1.7" stroke-linejoin="miter" />
                        <path d="M16 5 V17" stroke-width="1.7" />
                        <path d="M5 26 L16 17" stroke-width="1.7" />
                        <path d="M27 26 L16 17" stroke-width="1.7" />
                        <g fill="currentColor" stroke="none">
                            <circle cx="16" cy="5" r="2.4" />
                            <circle cx="5" cy="26" r="2.4" />
                            <circle cx="27" cy="26" r="2.4" />
                            <circle cx="16" cy="17" r="2.4" />
                        </g>
                    </svg>
                    Laravel Truss
                </a>

                {{-- Both versions, because the whole point of this application is
                     that the two move independently. The plugin reads `dev-main`
                     here: it is symlinked from the working tree, not installed
                     from a release. --}}
                <p style="margin-top: 0.25rem; font-size: 0.75rem; color: var(--gray-500);">
                    truss {{ $truss }} &middot; plugin {{ $plugin }}
                </p>
            </div>

            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                {{-- Guarded, because the page guards itself. With
                     `truss.enabled` false the page answers 403 and Filament
                     drops it from the navigation, and a card still offering the
                     link would be the one place in this panel that lies about
                     what is reachable. --}}
                @if (SchemaPage::canAccess())
                    <x-filament::link
                        color="gray"
                        :href="SchemaPage::getUrl()"
                        :icon="Heroicon::CircleStack"
                    >
                        Database schema
                    </x-filament::link>
                @endif

                <x-filament::link
                    color="gray"
                    :href="url('/truss')"
                    :icon="Heroicon::ArrowTopRightOnSquare"
                    rel="noopener noreferrer"
                    target="_blank"
                >
                    Truss dashboard
                </x-filament::link>

                {{-- The Filament guide rather than the site root: this card is in
                     a Filament panel, and the page it is about is documented
                     there. The plugin's own header link goes to the same place. --}}
                <x-filament::link
                    color="gray"
                    href="https://trussphp.com/filament/"
                    :icon="Heroicon::BookOpen"
                    rel="noopener noreferrer"
                    target="_blank"
                >
                    Documentation
                </x-filament::link>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
