<?php

namespace App\Filament\Resources\AvisoResource\Pages;

use App\Filament\Resources\AvisoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAviso extends CreateRecord
{
    protected static string $resource = AvisoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 