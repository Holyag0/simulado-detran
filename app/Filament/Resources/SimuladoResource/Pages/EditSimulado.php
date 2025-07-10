<?php

namespace App\Filament\Resources\SimuladoResource\Pages;

use App\Filament\Resources\SimuladoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSimulado extends EditRecord
{
    protected static string $resource = SimuladoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
