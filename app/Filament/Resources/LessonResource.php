<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Filament\Resources\LessonResource\RelationManagers;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('chapter_id')
                    ->relationship('chapter', 'title')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('content')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('chapter.title')
                    ->badge()
                    ->numeric(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                //

                Tables\Filters\SelectFilter::make('course_id')
                    ->relationship('course', 'title')
                    ->label('Course'),

                Tables\Filters\SelectFilter::make('chapter_id')
                    ->relationship('chapter', 'title')
                    ->label('Chapter'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            // ->reorderable('sort_order')
            ->reorderable(function (): ?string {
                $livewire = \Livewire\Livewire::current();
                $filters = $livewire->tableFilters ?? [];

                if (isset($filters['chapter_id']['value']) && !empty($filters['chapter_id']['value'])) {
                    return 'sort_order';
                }

                return null;
            })
            // ->defaultSort('chapter_id')
            // ->defaultSort('sort_order')
            ->defaultSort(function (Builder $query): Builder {
                return $query
                    ->orderBy('chapter_id')
                    ->orderBy('sort_order');
            })
            ->groups([
                Tables\Grouping\Group::make('chapter.sort_order')
                    ->label('Chapter')
                    ->collapsible()
                    ->getTitleFromRecordUsing(
                        fn(Lesson $record): string =>
                        $record->chapter->title
                    ),
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
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
