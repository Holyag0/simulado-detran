<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $pluralModelLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->placeholder('Digite o nome completo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->placeholder('exemplo@email.com')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cpf')
                    ->label('CPF')
                    ->placeholder('000.000.000-00')
                    ->maxLength(14),
                Forms\Components\TextInput::make('telefone')
                    ->label('Telefone')
                    ->placeholder('(11) 99999-9999')
                    ->maxLength(30),
                Forms\Components\TextInput::make('auto_escola')
                    ->label('Auto Escola')
                    ->placeholder('Nome da auto escola')
                    ->maxLength(255),
                Forms\Components\Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'aluno' => 'Aluno',
                        'admin' => 'Admin',
                    ])
                    ->default('aluno')
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->placeholder('Digite a senha')
                    ->password()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                    ->required(fn($context) => $context === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF'),
                Tables\Columns\TextColumn::make('telefone')
                    ->label('Telefone'),
                Tables\Columns\TextColumn::make('auto_escola')
                    ->label('Auto Escola'),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aluno' => 'success',
                        'admin' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'aluno' => 'Aluno',
                        'admin' => 'Admin',
                    ]),
            ])
            ->actions([
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
            // Você pode adicionar RelationManagers aqui se quiser ver tentativas do usuário, por exemplo
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
