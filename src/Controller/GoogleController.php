<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\Request;

class GoogleController extends AbstractController
{
    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        // // ** if you want to *authenticate* the user, then
        // // leave this method blank and create a Guard authenticator
        // // (read below)

        // /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient $client */
        // $client = $clientRegistry->getClient('google');

        // try {
        //     // the exact class depends on which provider you're using
        //     /** @var \League\OAuth2\Client\Provider\GoogleUser $user */
        //     $user = $client->fetchUser();

        //     // do something with all this new power!
        //     // e.g. $name = $user->getFirstName();
        //     // var_dump($user); die;
        //     // ...

        //     dd($user);

        // } catch (IdentityProviderException $e) {
        //     // something went wrong!
        //     // probably you should return the reason to the user
        //     var_dump($e->getMessage()); die;
        // }
    }

    #[Route('/connect/google', name: 'app_google_connect')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect([], ['prompt' => 'consent']);
    }

}
