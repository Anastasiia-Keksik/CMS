<?php


namespace App\Services;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CheckPathRoutes
{
    private $gen;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->gen = $generator;
    }

    public function checkPath($dynamicRouteName, $parameters = [])
    {

        try {
            $url = $this->gen->generate($dynamicRouteName, $parameters);
        } catch (RouteNotFoundException $e) {
            return "Route not found for: $dynamicRouteName";
        } catch (InvalidParameterException $e) {
            return "Invalid parameter for: $dynamicRouteName -> $parameters";
        } catch (MissingMandatoryParametersException $e) {
            return "Missing parameter for: $dynamicRouteName";
        }

        return "pass";
    }
}