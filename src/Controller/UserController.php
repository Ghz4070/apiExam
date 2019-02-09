<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractFOSRestController
{
    private $userRepository;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Rest\View(serializerGroups={"infoUser"})
     * @Rest\Get("/api/userSecure")
     */
    public function getApiUser()
    {
        return $this->view($this->getUser());
    }

    /**
     * @Rest\Patch("/api/userSecure/edit")
     */
    public function patchApiUser(Request $request, SubscriptionRepository $subscriptionRepository, CardRepository $cardRepository)
    {
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $address = $request->get('address');
        $country = $request->get('country');
        $subscription = $request->get('subscription')['id'];
        $cards = $request->get('cards')['name'];

        dd($cards);

        if ($firstname !== null) {
            $this->getUser()->setFirstname($firstname);
        }
        if ($lastname !== null) {
            $this->getUser()->setLastname($lastname);
        }
        if ($address !== null) {
            $this->getUser()->setAddress($address);
        }
        if ($country !== null) {
            $this->getUser()->setCountry($country);
        }
        if ($subscription !== null) {
            $findSubscription = $subscriptionRepository->find($subscription);
            $this->getUser()->setSubscription($findSubscription);
        }
        if ($cards !== null) {
            $findCards = $cardRepository->find($cards);
            $this->getUser()->setCards($findCards);
        }

        $this->em->persist($this->getUser());
        $this->em->flush();
        return $this->view($this->getUser());
    }

    /**
     * @Rest\View(serializerGroups={"infoUser"})
     * @Rest\Get("/api/userSecure/GetAllCards")
     */
    public function getApiCards()
    {
        return $this->view($this->getUser()->getCards());
    }

}
