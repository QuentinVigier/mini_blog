<?php

namespace App\Controller;

use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/initiate-payment', name: 'initiate_payment')]
    public function initiatePayment(PaymentService $paymentService): Response
    {
        $session = $paymentService->askCheckout();
        
        return $this->redirect($session->url);
    }

    #[Route('/payment-success', name: 'payment_success')]
    public function paymentSuccess(): Response
    {
        // Logique pour traiter un paiement réussi
        return $this->render('payment/success.html.twig');
    }

    #[Route('/payment-cancel', name: 'payment_cancel')]
    public function paymentCancel(): Response
    {
        // Logique pour traiter un paiement annulé
        return $this->render('payment/cancel.html.twig');
    }
}
