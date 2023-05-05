<?php

namespace App\Controller\Security;

use App\DomainObject\RegisterDomainObject;
use App\Form\Type\RegisterFormType;
use App\Manager\Admin\RegisterManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'api_security_register', methods: ['POST'])]
    public function __invoke(
        RegisterManager $registerManager,
        Request $request,
        NormalizerInterface $serializer
    ): JsonResponse
    {
        $dto = new RegisterDomainObject();
        $form = $this->createForm(RegisterFormType::class, $dto);
        $form->submit($request->toArray(), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $registerManager->createUserFromDTO($dto);
            return new JsonResponse($serializer->normalize($user));
        }

        return new JsonResponse([
            'errors' => $serializer->normalize($form),
        ], Response::HTTP_BAD_REQUEST);
    }
}
