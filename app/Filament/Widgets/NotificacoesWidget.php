<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Livewire\AdminNotificacoesSino;

class NotificacoesWidget extends Widget
{
    protected static string $view = 'filament.widgets.notificacoes-widget';

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return true;
    }
} 