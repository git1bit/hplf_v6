<?php

namespace App\Filament\Resources;

use App\Models\Category;
use Filament\Forms\Components\Card;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-s-users';

    protected static ?string $modelLabel = 'ユーザー'; //ここを追加

    public static function form(Form $form): Form
    {
        $categories = Category::all()->pluck('name', 'id')->toArray();

        return $form
            ->schema([
                Card::make()->schema([

                    //名前
                    Forms\Components\TextInput::make('name')->required()->label('企業名'),

                    //メアド：編集保存時にメアドのユニーク制限でエラーにならないようにignoreRecordをtrueにする
                    Forms\Components\TextInput::make('email')->required()->label('メールアドレス')
                        ->unique(ignoreRecord: true),

                    //パスワード：パスワードのハッシュ化や、編集時に都度再入力を求められないようにdehydrateを使う
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create')
                        ->same('password_confirmation')
                        ->label('パスワード'),

                    //パスワード確認フィールド
                    Forms\Components\TextInput::make('password_confirmation')
                        ->password()
                        ->required(fn (string $context): bool => $context === 'create')
                        ->dehydrated(false)
                        ->label('パスワード確認'),

                    Forms\Components\Toggle::make('is_admin')
                        ->label('管理者権限'),
                    Forms\Components\Select::make('company.category_id')
                        ->label('カテゴリ')
                        ->options($categories)
                        ->required(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('name')->label('名称')
                    ->searchable()
                    ->sortable(),
                //->url(fn ($user) => static::generateUrl('edit', $user)),
                Tables\Columns\TextColumn::make('email')->label('メールアドレス')->searchable()->sortable(),
                Tables\Columns\BooleanColumn::make('is_admin')->label('管理者権限')->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->is_admin) {
            return $query;
        } else {
            return $query->where('id', auth()->user()->id);
        }
    }

    /* public static function getEloquentQuery(): Builder
    {

        return parent::getEloquentQuery()->where('id', auth()->user()->id);
    } */
}
