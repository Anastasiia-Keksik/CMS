<?php

namespace App;

use App\Entity\Account;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeClient extends AbstractController
{
    private $em;
    private $stripe;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
        $this->stripe = new \Stripe\StripeClient(
            $_SERVER['stripe_secret_key']
        );
    }

    public function createCustomer(Account $user, $paymentToken){
        $customer = $this->stripe->customers->create([
            'email' => $user->getEmail()
        ]);

        $card = $this->stripe->customers->createSource(
            $customer->id,
            ['source' => $paymentToken]
        );

        $this->stripe->customers->update($customer->id, ['default_source' => $card]);

        $user->setStripeCustomerId($customer->id);
        $em = $this->em;
        $em->persist($user);
        $em->flush();

        return $customer;
    }

    public function updateCustomerCard($user, $paymentToken){
        $card = $this->stripe->customers->createSource(
            $user->getStripeCustomerId(),
            ['source' => $paymentToken]
        );

        $this->stripe->customers->update($user->getStripeCustomerId(), ['default_source' => $card]);
    }

    public function  createInvoiceItem($amount, Account $user, $currency, $description){
        return $this->stripe->invoiceItems->create([
            'amount' => $amount,
            'currency' => $currency,
            'customer' => $user->getStripeCustomerId(),
            'description' => $description
        ]);
    }

    public function createInvoice(Account $user, $payImmidiately = true){
        $invoice = $this->stripe->invoices->create([
            'customer' => $user->getStripeCustomerId()
        ]);

        if ($payImmidiately){
            $invoice->pay();
        }

        return $invoice;
    }
}