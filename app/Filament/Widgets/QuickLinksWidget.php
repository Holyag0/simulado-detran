<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickLinksWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-links-widget';
    protected static ?int $sort = -1; // Exibe no topo
} 