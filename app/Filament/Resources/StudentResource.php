<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Events\PromoteStudent;



use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Students Management';
    protected static ?string $recordTitleAttribute = 'first_name';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Wizard::make([
                Forms\Components\Wizard\Step::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255),
                        Forms\Components\TextInput::make('middle_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                        ->maxLength(255),
                    ]),

                Forms\Components\Wizard\Step::make('School Information')
                ->schema([

                        Forms\Components\TextInput::make('student_id')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('standard_id')
                                ->relationship('standard','name')
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            Forms\Components\TextInput::make('class_number')
                                ->required()
                                ->numeric(),                
                                ]),
                        ])->columns(2),

                Forms\Components\Wizard\Step::make('demoGraphics Data')
                    ->schema([
                        Forms\Components\TextInput::make('address_1')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address_2')
                            ->required()
                            ->maxLength(255),
                    ]),

                ])->columns(3)->columnSpanFull(),

            ]);
              
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('standard.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('standard 1')->query(fn(Builder $query): Builder => $query->where('standard_id',1) ),
               
                // Tables\Filters\SelectFilter::make('standard_id')
                //     ->options([
                //         1 => 'standard 1',
                //         2 => 'standard 2',
                //         3 => 'standard 3',
                //     ])->label('Select The Class'),
                
                Tables\Filters\SelectFilter::make('standard_id')
                    ->relationship('standard','name')
                    ->preload()
                    ->label('Select The Class'),
            ])  
            ->actions([
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Promote')
                    ->action(function(Student $record){
                        // dump($record);
                        // dispatch an event ;
                        event(new PromoteStudent($record));
                    })->color('success'),     
                    Tables\Actions\Action::make('Demote')
                    ->action(function(Student $record){
                        // dump($record);
                        $record->standard_id = $record->standard_id - 1 ;
                        $record->save();
                    })
                    ->color('danger')
                    ->requiresConfirmation(),
                    // ->deselectRecordsAfterAction(),
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Promote All')
                        ->action(function(Collection $records){
                            $records->each(function($record){
                                // $record->standard_id = $record->standard_id + 1;
                                // $record->save();
                                 // dispatch an event ;
                                event(new PromoteStudent($record));
                            });
                    }),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StandardRelationManager::class,
            RelationManagers\GuardiansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'First Name' => $record->first_name,
            'Middle Name' => $record->middle_name,
            'First Name' => $record->lastt_name,
            'Class' => $record->standard->name,
        ];
    }
}
