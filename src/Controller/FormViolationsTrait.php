<?php

namespace App\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

trait FormViolationsTrait
{
    private function getFormErrorResponse(FormInterface $form, NormalizerInterface $normalizer): JsonResponse
    {
        $violations = new ConstraintViolationList();
        foreach ($form->getErrors(deep: true) as $error) {
            $violations->add($error->getCause());
        }

        return new JsonResponse($normalizer->normalize($violations), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
