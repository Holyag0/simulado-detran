<?php

namespace App\Filament\Resources\SimuladoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TentativasRelationManager extends RelationManager
{
    protected static string $relationship = 'tentativas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Não permitir criar/editar tentativas manualmente por padrão
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Aluno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pontuacao')
                    ->label('Pontuação (%)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('acertos')
                    ->label('Acertos'),
                Tables\Columns\TextColumn::make('erros')
                    ->label('Erros'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => $state === 'finalizada' ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('iniciado_em')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i'),
                Tables\Columns\TextColumn::make('finalizado_em')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Não permitir criar tentativas manualmente
            ])
            ->actions([
                // Não permitir editar/deletar tentativas manualmente
            ])
            ->bulkActions([
                //
            ]);
    }
}
