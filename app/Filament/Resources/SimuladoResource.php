<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimuladoResource\Pages;
use App\Filament\Resources\SimuladoResource\RelationManagers;
use App\Models\Simulado;
use App\Services\SimuladoService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SimuladoResource extends Resource
{
    protected static ?string $model = Simulado::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationLabel = 'Simulados';
    
    protected static ?string $modelLabel = 'Simulado';
    
    protected static ?string $pluralModelLabel = 'Simulados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Simulado')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->rows(3)
                            ->maxLength(1000),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('tempo_limite')
                                    ->label('Tempo Limite (minutos)')
                                    ->numeric()
                                    ->default(30)
                                    ->minValue(1)
                                    ->required(),
                                
                                Forms\Components\TextInput::make('numero_questoes')
                                    ->label('Número de Questões')
                                    ->numeric()
                                    ->default(30)
                                    ->minValue(1)
                                    ->required(),
                            ]),
                        
                        Forms\Components\Toggle::make('ativo')
                            ->label('Simulado Ativo')
                            ->default(true),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tempo_limite')
                    ->label('Tempo (min)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('numero_questoes')
                    ->label('Questões')
                    ->sortable(),
                Tables\Columns\IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('questoes_count')
                    ->label('Questões Configuradas')
                    ->counts('questoes'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('ativo')
                    ->label('Apenas Ativos'),
            ])
            ->actions([
                Tables\Actions\Action::make('gerarQuestoes')
                    ->label('Gerar Questões')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->action(function (Simulado $record) {
                        $service = new SimuladoService();
                        $validacao = $service->validarConfiguracao($record);
                        
                        if (!$validacao['valido']) {
                            Notification::make()
                                ->title('Erro na configuração')
                                ->body(implode("\n", $validacao['erros']))
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        $service->gerarQuestoesParaSimulado($record);
                        
                        Notification::make()
                            ->title('Questões geradas com sucesso')
                            ->body("{$validacao['total_questoes']} questões foram adicionadas ao simulado")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Gerar Questões Aleatórias')
                    ->modalDescription('Isso irá substituir todas as questões atuais do simulado por questões aleatórias baseadas na configuração de categorias.')
                    ->modalSubmitActionLabel('Gerar Questões')
                    ->visible(fn($record) => $record->categorias()->count() > 0),
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Excluir selecionados'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestoesRelationManager::class,
            RelationManagers\TentativasRelationManager::class,
            RelationManagers\CategoriasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSimulados::route('/'),
            'create' => Pages\CreateSimulado::route('/create'),
            'edit' => Pages\EditSimulado::route('/{record}/edit'),
            'adicionar-questoes-existentes' => Pages\AdicionarQuestoesExistentes::route('/{record}/adicionar-questoes-existentes'),
        ];
    }
}
