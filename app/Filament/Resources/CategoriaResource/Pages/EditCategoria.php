<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Filament\Resources\CategoriaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoria extends EditRecord
{
    protected static string $resource = CategoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Excluir'),
        ];
    }

    protected function getSaveFormActionLabel(): string
    {
        return 'Salvar';
    }

    protected function getCancelFormActionLabel(): string
    {
        return 'Cancelar';
    }
}
