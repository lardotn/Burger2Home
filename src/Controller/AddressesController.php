<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddressesController extends AbstractController
{
    #[Route('/addresses', name: 'app_addresses', methods: ['GET'])]
    public function getUserAddresses(
        #[CurrentUser] ?User $user
    ): JsonResponse {
        return $this->json($user->getAddresses(), 200, [], ['groups' => 'addressDetail:read']);
    }

    #[Route('/addresses', name: 'user_new_addresses', methods: ['POST'])]
    public function setUserNewAddress(
        #[CurrentUser] ?User $user,
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        try {
            $jsonReceived = $request->getContent();

            $address = $serializer->deserialize($jsonReceived, Address::class, 'json');

            if (count($user->getAddresses()) >= 3) {
                return $this->json(['status' => 403, 'message' => "Reach the limit of addresses for this user."], 403);
            }

            $existingAddress = $entityManager->getRepository(Address::class)->findOneBy(['street' => $this->secureString($address->getStreet())]);

            if ($existingAddress) {
                return $this->json(['status' => 403, 'message' => "Address is already existing in database."], 403);
            }

            $newAddress = new Address();

            $newAddress->setOwner($user);
            $newAddress->setStreet($this->secureString($address->getStreet()));
            $newAddress->setPostalCode($this->secureString($address->getPostalCode()));
            $newAddress->setCity($this->secureString($address->getCity()));
            $newAddress->setIsActive(true);

            $errors = $validator->validate($newAddress);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $entityManager->persist($newAddress);
            $entityManager->flush();

            return $this->json(201);
        } catch (Exception $e) {
            return $this->json(['status' => 400, 'message' => $e], 400);
        }
    }

    #[Route('/addresses/{id}', name: 'user_update_address', methods: ['PUT'])]
    public function updateAddress(
        #[CurrentUser] ?User $user,
        Address $address,
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        try {
            $jsonReceived = $request->getContent();

            $addressUpdated = $serializer->deserialize($jsonReceived, Address::class, 'json');

            $existingAddress = $entityManager->getRepository(Address::class)->findOneBy(['id' => $this->secureString($address->getId())]);

            if ($existingAddress && $user->getAddresses()->contains($existingAddress)) {
                $existingAddress->setOwner($user);
                $existingAddress->setStreet($this->secureString($addressUpdated->getStreet()));
                $existingAddress->setPostalCode($this->secureString($addressUpdated->getPostalCode()));
                $existingAddress->setCity($this->secureString($addressUpdated->getCity()));

                $errors = $validator->validate($existingAddress);

                if (count($errors) > 0) {
                    return $this->json($errors, 400);
                }

                $entityManager->persist($existingAddress);
                $entityManager->flush();

                return $this->json(204);
            } else {
                return $this->json(404);
            }
        } catch (Exception $e) {
            return $this->json(['status' => 400, 'message' => $e], 400);
        }
    }

    #[Route('/addresses/{id}', name: 'user_delete_address', methods: ['DELETE'])]
    public function deleteUserAddress(
        #[CurrentUser] ?User $user,
        Address $address,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        try {
            $existingAddress = $entityManager->getRepository(Address::class)->findOneBy(['id' => $this->secureString($address->getId())]);

            if ($existingAddress && $user->getAddresses()->contains($existingAddress)) {
                $existingAddress->setOwner(null);
                $existingAddress->setIsActive(false);

                $errors = $validator->validate($existingAddress);

                if (count($errors) > 0) {
                    return $this->json($errors, 400);
                }

                $entityManager->persist($existingAddress);
                $entityManager->flush();

                return $this->json(204);
            } else {
                return $this->json(404);
            }
        } catch (Exception $e) {
            return $this->json(['status' => 400, 'message' => $e], 400);
        }
    }

    private function secureString(?string $text): ?string
    {
        return strtolower(trim(htmlspecialchars($text)));
    }
}
