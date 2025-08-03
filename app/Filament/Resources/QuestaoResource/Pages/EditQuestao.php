<?php

namespace App\Filament\Resources\QuestaoResource\Pages;

use App\Filament\Resources\QuestaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestao extends EditRecord
{
    protected static string $resource = QuestaoResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Garantir que os checkboxes sejam marcados corretamente
        $respostaCorreta = $data['resposta_correta'] ?? 'a';
        
        $data['resposta_correta_a'] = $respostaCorreta === 'a';
        $data['resposta_correta_b'] = $respostaCorreta === 'b';
        $data['resposta_correta_c'] = $respostaCorreta === 'c';
        $data['resposta_correta_d'] = $respostaCorreta === 'd';
        
        return $data;
    }

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
