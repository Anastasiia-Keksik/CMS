<?php
namespace App\Services;

use Omnipay\Omnipay;
class Payment
{
    /**
     * @return mixed
     */
    public function gateway()
    {
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername("sb-ah584349377@business.example.com");
        $gateway->setPassword("AbUp2Tmzd9KibaJBxnWfuSdrzEkbzkeROzDwHmjkd1BUB0IZhDb18txt07sVwn4eadMUF9IbP7vcm4dm");
        $gateway->setSignature("EKIGQFm81xogVjdBGxh-H40MXwHk6ZkIYLgvCeHRHHFZ08Oafs1jJoA_i8RPDgR2oTmIScLXoQgfMSVv");
        $gateway->setTestMode(true);
        return $gateway;
    }
    /**
     * @param array $parameters
     * @return mixed
     */
    public function purchase(array $parameters)
    {
        $response = $this->gateway()
            ->purchase($parameters)
            ->send();
        return $response;
    }
    /**
     * @param array $parameters
     */
    public function complete(array $parameters)
    {
        $response = $this->gateway()
            ->completePurchase($parameters)
            ->send();
        return $response;
    }
    /**
     * @param $amount
     */
    public function formatAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }
    /**
     * @param $order
     */
    public function getCancelUrl($order = "")
    {
        return $this->route('http://phpstack-275615-1077014.cloudwaysapps.com/cancel.php', $order);
    }
    /**
     * @param $order
     */
    public function getReturnUrl($order = "")
    {
        return $this->route('http://phpstack-275615-1077014.cloudwaysapps.com/return.php', $order);
    }
    public function route($name, $params)
    {
        return $name; // ya change hua hai
    }
}