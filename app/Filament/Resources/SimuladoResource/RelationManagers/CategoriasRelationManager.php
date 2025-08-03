<?php

namespace App\Filament\Resources\SimuladoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoriasRelationManager extends RelationManager
{
    protected static string $relationship = 'categorias';

    protected static ?string $recordTitleAttribute = 'nome';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('categoria_id')
                    ->label('Categoria')
                    ->relationship('categoria', 'nome')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('quantidade_questoes')
                    ->label('Quantidade de Questões')
                    ->placeholder('5')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->default(5),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.quantidade_questoes')
                    ->label('Quantidade de Questões')
                    ->sortable(),
                Tables\Columns\TextColumn::make('questoes_count')
                    ->label('Questões Disponíveis')
                    ->counts('questoes')
                    ->sortable(),
                Tables\Columns\ColorColumn::make('cor')
                    ->label('Cor'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Apenas Categorias Ativas'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Adicionar Categoria'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('Remover'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remover selecionadas'),
                ]),
            ]);
    }
}
