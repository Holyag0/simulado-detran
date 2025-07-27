<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestaoResource\Pages;
use App\Filament\Resources\QuestaoResource\RelationManagers;
use App\Models\Questao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestaoResource extends Resource
{
    protected static ?string $model = Questao::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Questões';
    protected static ?string $modelLabel = 'Questão';
    protected static ?string $pluralModelLabel = 'Questões';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('simulado_id')
                    ->label('Simulado')
                    ->relationship('simulado', 'titulo')
                    ->required(),
                Forms\Components\Textarea::make('pergunta')
                    ->label('Pergunta')
                    ->required()
                    ->rows(3),
                Forms\Components\Section::make('Alternativas')
                    ->description('Clique no ícone ✓ ao lado da alternativa para marcá-la como correta')
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\TextInput::make('alternativa_a')
                                    ->label('Alternativa A')
                                    ->required()
                                    ->prefix(fn($get) => $get('resposta_correta') === 'a' ? '✓' : 'A')
                                    ->prefixIcon(fn($get) => $get('resposta_correta') === 'a' ? 'heroicon-o-check-circle' : null)
                                    ->prefixIconColor('success')
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('marcar_a')
                                            ->icon('heroicon-o-check-circle')
                                            ->color('success')
                                            ->label('Marcar como correta')
                                            ->action(function ($livewire, $get, $set) {
                                                $set('resposta_correta', 'a');
                                            })
                                            ->visible(fn($get) => $get('resposta_correta') !== 'a')
                                    )
                                    ->extraAttributes(fn($get) => [
                                        'class' => $get('resposta_correta') === 'a' ? 'ring-2 ring-green-500' : ''
                                    ]),
                                Forms\Components\TextInput::make('alternativa_b')
                                    ->label('Alternativa B')
                                    ->required()
                                    ->prefix(fn($get) => $get('resposta_correta') === 'b' ? '✓' : 'B')
                                    ->prefixIcon(fn($get) => $get('resposta_correta') === 'b' ? 'heroicon-o-check-circle' : null)
                                    ->prefixIconColor('success')
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('marcar_b')
                                            ->icon('heroicon-o-check-circle')
                                            ->color('success')
                                            ->label('Marcar como correta')
                                            ->action(function ($livewire, $get, $set) {
                                                $set('resposta_correta', 'b');
                                            })
                                            ->visible(fn($get) => $get('resposta_correta') !== 'b')
                                    )
                                    ->extraAttributes(fn($get) => [
                                        'class' => $get('resposta_correta') === 'b' ? 'ring-2 ring-green-500' : ''
                                    ]),
                                Forms\Components\TextInput::make('alternativa_c')
                                    ->label('Alternativa C')
                                    ->required()
                                    ->prefix(fn($get) => $get('resposta_correta') === 'c' ? '✓' : 'C')
                                    ->prefixIcon(fn($get) => $get('resposta_correta') === 'c' ? 'heroicon-o-check-circle' : null)
                                    ->prefixIconColor('success')
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('marcar_c')
                                            ->icon('heroicon-o-check-circle')
                                            ->color('success')
                                            ->label('Marcar como correta')
                                            ->action(function ($livewire, $get, $set) {
                                                $set('resposta_correta', 'c');
                                            })
                                            ->visible(fn($get) => $get('resposta_correta') !== 'c')
                                    )
                                    ->extraAttributes(fn($get) => [
                                        'class' => $get('resposta_correta') === 'c' ? 'ring-2 ring-green-500' : ''
                                    ]),
                                Forms\Components\TextInput::make('alternativa_d')
                                    ->label('Alternativa D')
                                    ->required()
                                    ->prefix(fn($get) => $get('resposta_correta') === 'd' ? '✓' : 'D')
                                    ->prefixIcon(fn($get) => $get('resposta_correta') === 'd' ? 'heroicon-o-check-circle' : null)
                                    ->prefixIconColor('success')
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('marcar_d')
                                            ->icon('heroicon-o-check-circle')
                                            ->color('success')
                                            ->label('Marcar como correta')
                                            ->action(function ($livewire, $get, $set) {
                                                $set('resposta_correta', 'd');
                                            })
                                            ->visible(fn($get) => $get('resposta_correta') !== 'd')
                                    )
                                    ->extraAttributes(fn($get) => [
                                        'class' => $get('resposta_correta') === 'd' ? 'ring-2 ring-green-500' : ''
                                    ]),
                            ])
                    ]),
                Forms\Components\Hidden::make('resposta_correta')
                    ->default('a'),
                Forms\Components\Textarea::make('explicacao')
                    ->label('Explicação (opcional)')
                    ->rows(2),
                Forms\Components\Toggle::make('ativo')
                    ->label('Ativa')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('simulado.titulo')
                    ->label('Simulado')
                    ->sortable()
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criada em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('simulado_id')
                    ->label('Simulado')
                    ->relationship('simulado', 'titulo'),
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Status')
                    ->placeholder('Todas')
                    ->trueLabel('Ativas')
                    ->falseLabel('Inativas'),
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
                        ->label('Excluir selecionados'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Você pode adicionar RelationManagers aqui se quiser ver respostas da questão, por exemplo
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestaos::route('/'),
            'create' => Pages\CreateQuestao::route('/create'),
            'edit' => Pages\EditQuestao::route('/{record}/edit'),
        ];
    }
}
