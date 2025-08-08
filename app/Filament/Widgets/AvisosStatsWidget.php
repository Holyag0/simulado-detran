<?php

namespace App\Filament\Widgets;

use App\Models\Aviso;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AvisosStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalAvisos = Aviso::count();
        $avisosAtivos = Aviso::where('ativo', true)->count();
        $avisosPopup = Aviso::where('mostrar_popup', true)->where('ativo', true)->count();
        $avisosHoje = Aviso::whereDate('created_at', today())->count();

        return [
            Stat::make('Total de Avisos', $totalAvisos)
                ->description('Todos os avisos criados')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('primary'),

            Stat::make('Avisos Ativos', $avisosAtivos)
                ->description('Avisos em exibição')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Avisos Pop-up', $avisosPopup)
                ->description('Avisos com pop-up ativo')
                ->descriptionIcon('heroicon-m-bell')
                ->color('warning'),

            Stat::make('Avisos Hoje', $avisosHoje)
                ->description('Avisos criados hoje')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
} 