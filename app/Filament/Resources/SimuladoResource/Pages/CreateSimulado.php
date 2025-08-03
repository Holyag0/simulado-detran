<?php

namespace App\Filament\Resources\SimuladoResource\Pages;

use App\Filament\Resources\SimuladoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSimulado extends CreateRecord
{
    protected static string $resource = SimuladoResource::class;

    protected function getCreateFormActionLabel(): string
    {
        return 'Criar';
    }

    protected function getCreateAnotherFormActionLabel(): string
    {
        return 'Criar e criar outro';
    }
}
