<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Filament\Resources\CategoriaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoria extends CreateRecord
{
    protected static string $resource = CategoriaResource::class;

    protected function getCreateFormActionLabel(): string
    {
        return 'Criar';
    }

    protected function getCreateAnotherFormActionLabel(): string
    {
        return 'Criar e criar outro';
    }
}
