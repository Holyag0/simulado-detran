<?php

namespace App\Filament\Resources\SimuladoResource\RelationManagers;

use App\Models\Questao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestoesRelationManager extends RelationManager
{
    protected static string $relationship = 'questoes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('categoria_id')
                    ->label('Categoria')
                    ->relationship('categoria', 'nome')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Textarea::make('pergunta')
                    ->label('Pergunta')
                    ->placeholder('Digite a pergunta da questão')
                    ->required()
                    ->rows(3),
                Forms\Components\Section::make('Alternativas')
                    ->description('Marque a checkbox ao lado da alternativa correta')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('alternativa_a')
                                    ->label('Alternativa A')
                                    ->placeholder('Digite a alternativa A')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_a')
                                    ->label('Correta')
                                    ->default(fn ($get) => $get('resposta_correta') === 'a')
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Desmarca as outras opções
                                            $set('resposta_correta_b', false);
                                            $set('resposta_correta_c', false);
                                            $set('resposta_correta_d', false);
                                            $set('resposta_correta', 'a');
                                        }
                                    }),
                                Forms\Components\TextInput::make('alternativa_b')
                                    ->label('Alternativa B')
                                    ->placeholder('Digite a alternativa B')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_b')
                                    ->label('Correta')
                                    ->default(fn ($get) => $get('resposta_correta') === 'b')
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Desmarca as outras opções
                                            $set('resposta_correta_a', false);
                                            $set('resposta_correta_c', false);
                                            $set('resposta_correta_d', false);
                                            $set('resposta_correta', 'b');
                                        }
                                    }),
                                Forms\Components\TextInput::make('alternativa_c')
                                    ->label('Alternativa C')
                                    ->placeholder('Digite a alternativa C')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_c')
                                    ->label('Correta')
                                    ->default(fn ($get) => $get('resposta_correta') === 'c')
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Desmarca as outras opções
                                            $set('resposta_correta_a', false);
                                            $set('resposta_correta_b', false);
                                            $set('resposta_correta_d', false);
                                            $set('resposta_correta', 'c');
                                        }
                                    }),
                                Forms\Components\TextInput::make('alternativa_d')
                                    ->label('Alternativa D')
                                    ->placeholder('Digite a alternativa D')
                                    ->required(),
                                Forms\Components\Checkbox::make('resposta_correta_d')
                                    ->label('Correta')
                                    ->default(fn ($get) => $get('resposta_correta') === 'd')
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            // Desmarca as outras opções
                                            $set('resposta_correta_a', false);
                                            $set('resposta_correta_b', false);
                                            $set('resposta_correta_c', false);
                                            $set('resposta_correta', 'd');
                                        }
                                    }),
                            ])
                    ]),
                Forms\Components\Hidden::make('resposta_correta')
                    ->default('a'),
                Forms\Components\Textarea::make('explicacao')
                    ->label('Explicação (opcional)')
                    ->placeholder('Explique por que esta é a resposta correta')
                    ->rows(2),
                Forms\Components\Toggle::make('ativo')
                    ->label('Ativa')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pergunta')
                    ->label('Pergunta')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('alternativa_a')->label('A'),
                Tables\Columns\TextColumn::make('alternativa_b')->label('B'),
                Tables\Columns\TextColumn::make('alternativa_c')->label('C'),
                Tables\Columns\TextColumn::make('alternativa_d')->label('D'),
                Tables\Columns\TextColumn::make('resposta_correta')
                    ->label('Correta')
                    ->formatStateUsing(fn($state) => strtoupper($state)),
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativa')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nova Questão'),
                Tables\Actions\Action::make('adicionarQuestoesExistentes')
                    ->label('Adicionar Questões Existentes')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->url(fn () => route('filament.admin.resources.simulados.adicionar-questoes-existentes', ['simulado' => $this->getOwnerRecord()]))
                    ->openUrlInNewTab(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Excluir selecionadas'),
                ]),
            ]);
    }
}
