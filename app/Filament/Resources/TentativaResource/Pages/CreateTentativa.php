<?php

namespace App\Filament\Resources\TentativaResource\Pages;

use App\Filament\Resources\TentativaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTentativa extends CreateRecord
{
    protected static string $resource = TentativaResource::class;

    protected function getCreateFormActionLabel(): string
    {
        return 'Criar';
    }

    protected function getCreateAnotherFormActionLabel(): string
    {
        return 'Criar e criar outro';
    }
}
