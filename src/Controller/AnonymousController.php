<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AnonymousController extends AbstractFOSRestController
{
    private $subscriptionRepository;
    private $userRepository;
    private $em;

    public function __construct(UserRepository $userRepository, SubscriptionRepository $subscriptionRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->em = $em;
    }

    /**
     * @Rest\View(serializerGroups={"anonymousUser"})
     * @Rest\Get("/api/anonymous/user/{id}")
     */
    public function getApiUser(User $user) // Afficher certain info de user en fonction de l'id
    {
        return $this->view($user);
    }

    /**
     * @Rest\View(serializerGroups={"anonymousUser"})
     * @Rest\Get("/api/anonymous/users")
     */
    public function getApiUsers() // Affiche tous les user avec les infos demandé
    {
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }

    /**
     * @Rest\View(serializerGroups={"infoSubscription"})
     * @Rest\Get("/api/anonymous/subscription/{id}")
     */
    public function getApiSubscription(Subscription $subscription) // Afficher la subscription en fonction de l'id
    {
        return $this->view($subscription);
    }

    /**
     * @Rest\View(serializerGroups={"infoSubscription"})
     * @Rest\Get("/api/anonymous/subscriptions")
     */
    public function getApiSubscriptions() // Afficher tous les Subscription
    {
        $subscription = $this->subscriptionRepository->findAll();
        return $this->view($subscription);
    }

    /**
     * @Rest\View(serializerGroups={"anonymousUser", "infoSubscription"})
     * @Rest\Post("/api/anonymous/postUser")
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postApiUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }

}
