<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getCreateFormActionLabel(): string
    {
        return 'Criar';
    }

    protected function getCreateAnotherFormActionLabel(): string
    {
        return 'Criar e criar outro';
    }
}
