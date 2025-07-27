<?php

namespace App\Filament\Resources\QuestaoResource\Pages;

use App\Filament\Resources\QuestaoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestao extends CreateRecord
{
    protected static string $resource = QuestaoResource::class;

    protected function getCreateFormActionLabel(): string
    {
        return 'Criar';
    }

    protected function getCreateAnotherFormActionLabel(): string
    {
        return 'Criar e criar outro';
    }
}
