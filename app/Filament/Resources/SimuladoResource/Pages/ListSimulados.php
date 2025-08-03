<?php

namespace App\Filament\Resources\SimuladoResource\Pages;

use App\Filament\Resources\SimuladoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSimulados extends ListRecords
{
    protected static string $resource = SimuladoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo Simulado'),
        ];
    }
}
