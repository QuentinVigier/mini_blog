<?php

namespace App\Service;

use Stripe\Stripe;
use App\Entity\Subscription;
use Stripe\Checkout\Session;
use App\Service\AbstractService;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentService 
{
    /**
     * Propriétés du service
     * - Offre sélectionnée
     * - Nom de domaine
     * - Clé API Stripe
     * - Utilisateur courant
     */
    private $domain, $apiKey, $user;

    public function __construct(
        private ParameterBagInterface $parameter, 
        private readonly Security $security, 
        private EntityManagerInterface $em
    )
    {
        $this->parameter = $parameter;
        $this->apiKey = $this->parameter->get('stripe_api_sk');
        $this->domain = 'https://127.0.0.1:8000';
        $this->user = $security->getUser();
    }

    /**
     * askCheckout()
     * Méthode permettant de créer une session de paiement Stripe
     * @return Stripe\Checkout\Session
     */
    public function askCheckout(): ?Session
    {
        Stripe::setApiKey($this->apiKey); // Établissement de la connexion (requête API)        
        $checkoutSession = Session::create([
            'customer_email' => $this->user->getEmail(),
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => 100, // Stripe utilise des centimes
                    'product_data' => [ // Les informations du produit sont personnalisables
                        'name' => 'Premium',
                        'description' => 'Premium offer',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->domain . '/payment-success',
            'cancel_url' => $this->domain . '/payment-cancel',
            'automatic_tax' => [
                'enabled' => false,
            ],
        ]);

        return $checkoutSession;
    }

}