<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Card;
use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';
    protected static ?string $activeNavigationIcon = 'heroicon-s-office-building';

    protected static ?string $modelLabel = '企業'; //ここを追加

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([

                    //
                    Forms\Components\Toggle::make('is_published')
                        ->required()
                        ->label('公開'),
                    /* BelongstoSelect::make('user_id')
                        ->relationship('user', 'name')
                        ->label('企業名'), */
                    /* BelongstoSelect::make('category_id')
                        ->options(
                            Category::query()->pluck('name', 'id')->toArray()
                        )
                        ->label('カテゴリ')
                        ->placeholder('カテゴリを選択してください')
                        ->required(), */

                    /* CheckboxList::make('category_id')
                        ->relationship('category', 'name')
                        ->columns(3)
                        ->label('業種カテゴリ')
                        ->visible(fn (string $context): bool => $context === 'create'), */
                    /* Forms\Components\TextInput::make('name')
                        ->required()
                        ->label('会社名')
                        ->columnSpan('full')
                        ->maxLength(255), */
                    Forms\Components\Textarea::make('catchphrase')
                        ->label('キャッチフレーズ')
                        ->columnSpan('full')
                        ->maxLength(65535)
                        ->hint("キャッチフレーズやスローガンを入力いただけます。"),
                    FileUpload::make('image')
                        ->label('紹介画像')
                        //->required()
                        ->multiple()
                        //->minFiles(1)
                        ->maxFiles(6)
                        ->image()
                        ->enableReordering()
                        ->enableDownload()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('3:2')
                        ->imageResizeTargetWidth('1024')
                        ->imageResizeTargetHeight('768')
                        ->directory('companies')
                        ->columnSpan('full')
                        ->hint("最低1枚は登録必須で、最大6枚まで登録可能。<br>先頭の画像がメインイメージとなります（登録後入替可能）。"),
                    FileUpload::make('strength_image1')
                        ->storeFileNamesIn('attachment_file_names')
                        ->image()
                        ->maxFiles(1)
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('3:2')
                        ->imageResizeTargetWidth('1024')
                        ->imageResizeTargetHeight('768')
                        ->directory('companies')
                        ->label('強み画像1'),
                    Forms\Components\Textarea::make('strength_text1')
                        ->maxLength(65535)
                        ->label('強みテキスト1'),
                    FileUpload::make('strength_image2')
                        ->storeFileNamesIn('attachment_file_names')
                        ->image()
                        ->maxFiles(1)
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('3:2')
                        ->imageResizeTargetWidth('1024')
                        ->imageResizeTargetHeight('768')
                        ->directory('companies')
                        ->imagePreviewHeight('250')
                        ->panelLayout('integrated')
                        ->label('強み画像2'),
                    Forms\Components\Textarea::make('strength_text2')
                        ->maxLength(65535)
                        ->label('強みテキスト2'),
                    FileUpload::make('strength_image3')
                        ->storeFileNamesIn('attachment_file_names')
                        ->image()
                        ->maxFiles(1)
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('3:2')
                        ->imageResizeTargetWidth('1024')
                        ->imageResizeTargetHeight('768')
                        ->directory('companies')
                        ->label('強み画像3'),
                    Forms\Components\Textarea::make('strength_text3')
                        ->maxLength(65535)
                        ->label('強みテキスト3'),
                    /* Forms\Components\Textarea::make('google_map')
                        ->columnSpan('full')
                        ->maxLength(65535), */
                    Forms\Components\Textarea::make('address')
                        //->required()
                        ->label('所在地')
                        ->maxLength(65535),
                    Forms\Components\TextInput::make('phone_number')
                        ->tel()
                        //->required()
                        ->label('電話番号')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('fax_number')
                        ->tel()
                        ->label('FAX番号')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('business_hours')
                        ->label('営業時間')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('holiday')
                        ->label('定休日')
                        ->maxLength(255),
                    Forms\Components\Toggle::make('parking_available')
                        ->required()
                        ->label('駐車場の有無'),
                    Forms\Components\TextInput::make('parking_slots')
                        ->label('駐車可能台数'),
                    Forms\Components\TextInput::make('payment_methods')
                        ->label('支払方法')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('website_url')
                        ->url()
                        ->label('ホームページアドレス')
                        ->maxLength(255),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                /* Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(), */
                Tables\Columns\TextColumn::make('name')
                    ->label('企業名')
                    //->searchable()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_published')
                    ->label('公開')
                    //->searchable()
                    ->sortable(),
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
            //CompanyResource\RelationManagers\CategoriesRelationManager::class, //追加
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->is_admin) {
            return $query;
        } else {
            return $query->where('id', auth()->user()->company->id);
        }
    }

    /* public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', auth()->user()->company->id);
    } */
}
