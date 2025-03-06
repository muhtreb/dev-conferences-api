<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueValueInEntity extends Constraint
{
    public string $message = 'Cette valeur est déjà utilisée';
    public ?string $ignoreId = null;

    public function __construct(
        public string $entityClass,
        public string $field,
        ?string $message = null,
        ?string $ignoreId = null,
        ?array $groups = null,
        $payload = null,
    ) {
        parent::__construct([], $groups, $payload);

        $this->message = $message ?? $this->message;
        $this->ignoreId = $ignoreId ?? $this->ignoreId;
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}
