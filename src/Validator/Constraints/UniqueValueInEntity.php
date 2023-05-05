<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueValueInEntity extends Constraint
{
    public string $message = 'Cette valeur est déjà utilisée';
    public string $entityClass;
    public string $field;
    public ?string $ignoreId = null;

    public function getRequiredOptions(): array
    {
        return ['entityClass', 'field'];
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return get_class($this) . 'Validator';
    }
} 