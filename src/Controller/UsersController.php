<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UsersController extends AbstractController
{
    #[Route('/signup', name: 'app_users_signup', methods: ['POST'])]
    public function userSignup(
        Request $request, 
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {        
        try {
            $jsonRecu = $request->getContent();

            $user = $serializer->deserialize($jsonRecu, User::class, 'json');
    
            $user->setEmail(trim(htmlspecialchars($user->getEmail())));
            $user->setPassword(trim($user->getPassword()));
    
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);
    
            $errors = $validator->validate($user);
    
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
    
            $em->persist($user);
            $em->flush();
    
            return $this->json(201);
        } catch (Exception $e) {
            return $this->json(['status' => 400, 'message' => $e], 400);
        }
    }

    #[Route('/users/current', name: 'app_users_current', methods: ['GET'])]
    public function getCurrentUser(#[CurrentUser] User $user): JsonResponse
    {
        return $this->json($user, 200, [], ['groups' => 'userDetail:read']);
    }
}
