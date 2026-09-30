<?php

namespace App\Core\DTOs;

use BackedEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonSerializable;
use Symfony\Component\Mime\Address;

/**
 * @phpstan-consistent-constructor
 */
abstract class AbstractDTO implements Arrayable, JsonSerializable
{
    public function toArray(): array
    {
        return collect(get_object_vars($this))->mapWithKeys(function ($value, $key) {
            if ($value instanceof AbstractDTO) {
                $value = $value->toArray();
            } elseif (is_array($value)) {
                $value = array_map(function (mixed $v) {
                    if ($v instanceof AbstractDTO) {
                        return $v->toArray();
                    }
                    return $v;
                }, $value);
            } elseif ($value instanceof Collection) {
                $value = $value->toArray();
            } elseif ($value instanceof Arrayable) {
                $value = $value->toArray();
            } elseif ($value instanceof Carbon) {
                $value = $value->toISOString(true);
            } elseif ($value instanceof BackedEnum) {
                $value = $value->value;
            } elseif ($value instanceof Address) {
                $value = $value->getAddress();
            }

            return [Str::snake($key) => $value];
        })->toArray();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Возвращает массив данных без null значений.
     * Полезно для частичных обновлений, когда не нужно перезаписывать существующие поля.
     */
    public function toArrayWithoutNulls(): array
    {
        return array_filter($this->toArray(), fn ($value) => $value !== null, ARRAY_FILTER_USE_BOTH);
    }

    protected static array $fieldMapping = [];

    public static function fromRequest(Request $request): static
    {
        return static::fromArray($request->all());
    }

    public static function fromRequestValidated(FormRequest $request): static
    {
        return static::fromArray($request->validated());
    }

    public static function fromArray(array $array): static
    {
        return static::reader(new static(), $array);
    }

    public static function fromCollection(Collection $collection): static
    {
        return static::reader(new static(), $collection);
    }

    public static function setFieldMapping(array $mapping): void
    {
        $validatedMapping = [];

        foreach ($mapping as $externalKey => $internalKey) {
            if (empty($externalKey) || empty($internalKey)) {
                continue;
            }

            $externalKey = (string) $externalKey;
            $internalKey = (string) $internalKey;

            $validatedMapping[$externalKey] = $internalKey;
        }

        static::$fieldMapping = $validatedMapping;
    }


    public static function getFieldMapping(): array
    {
        return static::$fieldMapping;
    }

    public static function setDateFrom(mixed $date): ?Carbon
    {
        return $date === null || $date === '' ? null : Carbon::parse($date);
    }

    public static function setDateTo(mixed $date): ?Carbon
    {
        return $date === null || $date === '' ? null : Carbon::parse($date);
    }

    /**
     * @param  static  $class
     * @return static
     */
    protected static function reader(self $class, iterable $iterable): static
    {
        foreach ($iterable as $key => $value) {
            $mappedKey = static::$fieldMapping[$key] ?? $key;

            $studlyKey = Str::studly($mappedKey);
            $lcFirstKey = lcfirst($studlyKey);

            if (method_exists(static::class, 'set' . $studlyKey)) {
                $value = static::{'set' . $studlyKey}($value);
            }

            if (property_exists($class, $lcFirstKey)) {
                $class->{$lcFirstKey} = $value;
            }
        }


        return $class;
    }
}
