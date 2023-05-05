<?php

namespace App\Validator\Constraints;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueValueInEntityValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueValueInEntity) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\NotNull');
        }

        if (empty($value)) {
            return;
        }

        $entityRepository = $this->em->getRepository($constraint->entityClass);

        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->andWhere($expr->eq($constraint->field, $value));
        if ($ignoreId = $constraint->ignoreId) {
            $criteria->andWhere($expr->neq('id', $ignoreId));
        }

        $searchResults = $entityRepository->matching($criteria);

        if ($searchResults->count() > 0) {
            $this->context
                ->buildViolation($constraint->message)
                ->setTranslationDomain('messages')
                ->addViolation();
        }
    }
}