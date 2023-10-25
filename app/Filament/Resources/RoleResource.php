<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
//EA 10 Oct 2023 - Use permission role model instead of default role
//EA 10 Oct 2023 - Added field for permission role
//EA 11 Oct 2023 - Added card for permission role
//use App\Models\Role;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoleResource\RelationManagers;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    //EA 25 Oct 2023 - Customise Navigation
    //Setting Icons
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    //Sorting navigation items
    protected static ?int $navigationSort = 2;
    //Grouping navigation items
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //EA 25 Oct 2023 - Added ignore current record unique checking
                //EA 11 Oct 2023 - Added card for permission role
                Card::make()->schema([
                    //EA 10 Oct 2023 - Added field for permission role
                            TextInput::make('name')
                            ->minLength(2)
                            ->maxLength(255)
                            ->required()
                            ->unique(ignoreRecord: true)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //EA 10 Oct 2023 - Added column for permission role
                TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('updated_at'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }    
}
