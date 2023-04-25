<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Card;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $activeNavigationIcon = 'heroicon-s-collection';

    protected static ?string $modelLabel = '業種'; //ここを追加

    // 追加: canSeeResource()メソッドのオーバーライド
    public static function canSeeResource(): bool
    {
        // 現在のユーザーが管理者の場合にtrueを返し、それ以外の場合にfalseを返します。
        return auth()->user() && auth()->user()->is_admin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    // ここに編集したい項目を追加する
                    Forms\Components\TextInput::make('name')
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('slug', Str::slug($state));
                        })
                        ->required()
                        ->label('業種')
                        ->hint("業種名を入力"),
                    Forms\Components\TextInput::make('slug')->required()->label('スラグ'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ここに表示したい項目を追加する
                Tables\Columns\TextColumn::make('name')->label('業種')->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('スラグ')->sortable(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
