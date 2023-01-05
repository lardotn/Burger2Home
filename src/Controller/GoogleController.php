<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GoogleController extends AbstractController
{
    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(
        ClientRegistry $clientRegistry,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $JWTManager
    ): JsonResponse {
        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient $client */
        $client = $clientRegistry->getClient('google');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\GoogleUser $user */
            $googleUser = $client->fetchUser();

            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['oauth_id' => $googleUser->getId()]);

            if ($existingUser) {
                return $this->json(
                    array(
                        "currentUser" => $existingUser,
                        'token' => $JWTManager->create($existingUser)
                    ), 200, [], ['groups' => 'userDetail:read']
                );
            }

            $newUser = new User();
    
            $newUser->setEmail($this->secureString($googleUser->getEmail()));
            $newUser->setFirstName($this->secureString($googleUser->getFirstName()));
            $newUser->setLastName($this->secureString($googleUser->getLastName()));
            $newUser->setOauthId($this->secureString($googleUser->getId()));
            $newUser->setFidelityPoint(0);
            $newUser->setRoles(['ROLE_USER']);

            $errors = $validator->validate($newUser);
    
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
    
            $entityManager->persist($newUser);
            $entityManager->flush();

            return $this->json(
                array(
                    "currentUser" => $newUser,
                    'token' => $JWTManager->create($newUser)
                ), 200, [], ['groups' => 'userDetail:read']
            );

        } catch (IdentityProviderException $e) {
            return $this->json(['status' => 400, 'message' => $e], 400);
        } 
    }

    #[Route('/connect/google', name: 'app_google_connect')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect([], []);
    }

    private function secureString(?string $text): ?string
    {
        return strtolower(trim(htmlspecialchars($text)));
    }
}
