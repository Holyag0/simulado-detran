<?php

namespace App\Filament\Resources\TentativaResource\Pages;

use App\Filament\Resources\TentativaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTentativa extends EditRecord
{
    protected static string $resource = TentativaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
