<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

/**
 * The Truss card on the dashboard, beside Filament's own.
 *
 * Demo only, and it belongs here rather than in the plugin: a plugin that puts
 * a card about itself on somebody's dashboard has invited itself in. Here it is
 * the point of the application, and it gives the demo a place to say which two
 * versions are actually running.
 */
class TrussInfoWidget extends Widget
{
    protected static ?int $sort = -1;

    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.truss-info-widget';
}
