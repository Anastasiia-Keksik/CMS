<?php

namespace App\Controller;

use App\Repository\UserPrivateForumRepository;
use App\Services\PayPal;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class TestController extends AbstractController
{
    /**
     *  @Route("/test", name="test")
     */
    public function test(){


        return $this->render("smartadmin/empty.twig");
    }

    /**
     * @Route("/testOrder", name="testOrder")
     */
    public function testOrder(PayPal $payPal){

        //$request = new OrdersCreateRequest();
        //$request->headers["prefer"] = "return=representation";
        //$request->headers["PayPal-Partner-Attribution_Id"] = "ELGZX6D579E5Q";

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = self::buildRequestBody();
        // 3. Call PayPal to set up a transaction
        $client = $payPal->client();
        $response = $client->execute($request);
        if (true)
        {
            print "Status Code: {$response->statusCode}\n";
            print "Status: {$response->result->status}\n";
            print "Order ID: {$response->result->id}\n";
            print "Intent: {$response->result->intent}\n";
            print "Links:\n";
            foreach($response->result->links as $link)
            {
                print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
            }

            // To print the whole response body, uncomment the following line
            // echo json_encode($response->result, JSON_PRETTY_PRINT);
        }

        //;

        // 4. Return a successful response to the client.
        return new JsonResponse($request);
    }

    private static function buildRequestBody()
    {
        return array(
            'intent' => 'CAPTURE',
            'application_context' =>
                array(
                    'return_url' => 'https://example.com/return',
                    'cancel_url' => 'https://example.com/cancel'
                ),
            'purchase_units' =>
                array(
                    0 =>
                        array(
                            'amount' =>
                                array(
                                    'currency_code' => 'USD',
                                    'value' => '220.00'
                                )
                        )
                )
        );
    }

}
