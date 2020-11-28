<?php

namespace App\Controller\Payment;

use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;

class Payment
{
    private $pay;
    private $card;

    function setcard($value)
    {

        try {
            $card = [
                'number' => $value['card'],
                'expiryMonth' => $value['expiremonth'],
                'expiryYear' => $value['expireyear'],
                'cvv' => $value['cvv']
            ];
            $ccard = new CreditCard($card);
            $ccard->validate();
            $this->card = $card;
            return true;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }

    function makepayment($value)
    {
        try {

// Setup payment Gateway
            $pay = Omnipay::create('Stripe');
            $pay->setApiKey('sk_test_8Fj878j44BkGFAstQq9jysL700ie9jhjRd');
// Send purchase request
            $response = $pay->purchase(
                [
                    'amount' => $value['amount'],
                    'currency' => $value['currency'],
                    'card' => $this->card
                ]
            )->send();

// Process response
            if ($response->isSuccessful()) {

                return "Thankyou for your payment";

            } elseif ($response->isRedirect()) {

// Redirect to offsite payment gateway
                return $response->getMessage();

            } else {
// Payment failed
                return $response->getMessage();
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}