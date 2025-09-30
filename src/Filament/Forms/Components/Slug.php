<?php

namespace Novius\LaravelFilamentSlug\Filament\Forms\Components;

use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Component;

class Slug extends TextInput
{
    protected ?TextInput $fromField = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hintAction(Action::make($this->getName().'_lock')
            ->label(function () {
                return $this->isReadOnly() ?
                    trans('laravel-filament-slug::messages.unlock') :
                    trans('laravel-filament-slug::messages.lock');
            })
            ->hidden(function (Get $get, $schemaOperation) {
                if ($schemaOperation === 'view') {
                    return true;
                }

                return empty($get($this->getStatePath(false))) || $this->evaluate($this->isReadOnly);
            })
            ->action(function (Get $get, Set $set) {
                $locked = $get($this->getName().'_locked');
                $set($this->getName().'_locked', ! $locked);
            }),
        );
    }

    public function isReadOnly(): bool
    {
        $readOnly = (bool) $this->evaluate($this->isReadOnly);
        if (! $readOnly) {
            $readOnly = (bool) $this->evaluate(function (?Model $record, Get $get, Set $set) {
                $locked = $get($this->getName().'_locked');
                if ($locked === null) {
                    $locked = $record !== null;
                    $set($this->getName().'_locked', $locked);
                }

                return $locked;
            });
        }

        return $readOnly;
    }

    public function fromField(TextInput $field, ?Closure $shouldSkipGenerateSlug = null): static
    {
        $this->fromField = $field;
        $field->live(onBlur: true);
        $field->afterStateUpdated(function (Set $set, Get $get, ?Model $record, ?string $old, ?string $state) use ($field, $shouldSkipGenerateSlug) {
            $shouldSkip = (bool) $field->evaluate($shouldSkipGenerateSlug);
            if ($shouldSkip) {
                return;
            }
            if ($get($this->getName().'_locked')) {
                return;
            }
            $oldSlug = $get($this->getStatePath(false));
            if (! empty($oldSlug) && $oldSlug !== Str::slug($old)) {
                return;
            }

            $set('slug', Str::slug($state));
        });
        $this->afterStateUpdated(function (Set $set, Get $get, ?Model $record, ?string $old, ?string $state) use ($field) {
            if (empty($state)) {
                $set($this->getStatePath(false), Str::slug($get($field->getStatePath(false))));
            }
        });

        return $this;
    }
}
