<?php

namespace App\Filament\Resources\TentativaResource\Pages;

use App\Filament\Resources\TentativaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTentativas extends ListRecords
{
    protected static string $resource = TentativaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo Resultado'),
        ];
    }
}
