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
    
    protected static ?string $title = 'Categorias';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('categoria_id')
                    ->label('Categoria')
                    ->options(\App\Models\Categoria::where('ativo', true)->pluck('nome', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $categoria = \App\Models\Categoria::find($state);
                            if ($categoria) {
                                $questoesDisponiveis = $categoria->questoes()->where('ativo', true)->count();
                                $set('quantidade_questoes', min(5, $questoesDisponiveis));
                            }
                        }
                    }),
                Forms\Components\TextInput::make('quantidade_questoes')
                    ->label('Quantidade de Questões')
                    ->placeholder('5')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->default(5)
                    ->helperText(function ($get) {
                        $categoriaId = $get('categoria_id');
                        if ($categoriaId) {
                            $categoria = \App\Models\Categoria::find($categoriaId);
                            if ($categoria) {
                                $questoesDisponiveis = $categoria->questoes()->where('ativo', true)->count();
                                return "Questões disponíveis nesta categoria: {$questoesDisponiveis}";
                            }
                        }
                        return '';
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Funcionalidade em Desenvolvimento')
            ->emptyStateDescription('A configuração de categorias por simulado está sendo implementada. Em breve você poderá configurar 
            quantas questões de cada categoria deseja em seus simulados e gerar questoes automaticamente apartir da configuração de categorias.')
            ->emptyStateIcon('heroicon-o-wrench-screwdriver')
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
                    ->label('Adicionar Categoria')
                    ->disabled()
                    ->tooltip('Funcionalidade em desenvolvimento')
                    ->icon('heroicon-o-wrench-screwdriver'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->disabled()
                    ->tooltip('Funcionalidade em desenvolvimento'),
                Tables\Actions\DeleteAction::make()
                    ->label('Remover')
                    ->disabled()
                    ->tooltip('Funcionalidade em desenvolvimento'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remover selecionadas')
                        ->disabled()
                        ->tooltip('Funcionalidade em desenvolvimento'),
                ]),
            ]);
    }
}
