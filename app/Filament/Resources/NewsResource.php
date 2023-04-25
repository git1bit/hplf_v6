<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Card;
use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $activeNavigationIcon = 'heroicon-s-newspaper';

    protected static ?string $modelLabel = 'お知らせ'; //ここを追加

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $company = $user->company;
        $category_id = $company->category_id;
        return $form
            ->schema([
                Card::make()->schema([

                    //
                    Forms\Components\Toggle::make('is_published')
                        ->required()
                        ->label('公開'),
                    Forms\Components\TextInput::make('title')
                        ->label('タイトル')
                        ->maxLength(50),
                    Forms\Components\TextArea::make('details')
                        ->label('本文'),
                ])
            ])
            /* ->default([
                'company_id' => $company->id,
                'category_id' => $category_id,
            ]) */;
    }

    public static function table(Table $table): Table
    {
        //$current_user_company_id = Auth::user()->company_id;

        return $table

            //->modelQueryBuilder(fn (Builder $query) => $query->where('company_id', $current_user_company_id))

            ->columns([
                //
                /* Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(), */
                Tables\Columns\TextColumn::make('title')
                    ->label('タイトル')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日')
                    ->dateTime('Y年m月d日'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('作成日')
                    ->dateTime('Y年m月d日'),
                Tables\Columns\BooleanColumn::make('is_published')
                    ->label('公開')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                /* Filter::make('自分の投稿')
                    ->query(function (Builder $query) {
                        $user = Auth::user();
                        $company_id = $user->company->id;
                        return $query->where('company_id', $company_id);
                    })->default(), */])
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->is_admin) {
            return $query;
        } else {
            return $query->whereHas('company', function ($query) {
                $query->where('id', auth()->user()->company->id);
            });
        }
    }

    /* public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('company_id', auth()->user()->company->id);
    } */
    /* public static function canEdit($record): bool
    {
        // ログインしているユーザーを取得
        $user = Auth::user();

        // ユーザーが投稿者である場合のみ編集を許可
        if ($user->company_id === $record->company_id) {
            return true;
        }

        return false;
    } */
}
