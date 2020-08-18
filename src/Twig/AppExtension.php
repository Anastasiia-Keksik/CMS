<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('csort', function ($trav, array $options = []) {
                return $this->customSort($trav, $options);
            }, ['is_variadic' => true]),
        ];
    }

    public function customSort($array, $options)
    {
        usort($array, function ($a, $b) use ($options) {
            $sortFunc = array_shift($options);
            return $sortFunc($this->getToLevel($a, $options), $this->getToLevel($b, $options));
        });
        return $array;
    }

    /**
     * Recursive function to get to the right element of the multi-dimensial array to compare
     *
     * @param $trav
     * @param array $levels
     * @return mixed
     */
    protected function getToLevel($trav, array $levels = [])
    {
        if (count($levels) > 1) {
            $lvl = array_shift($levels);
            return $this->getToLevel($trav[$lvl], $levels);
        } elseif (count($levels) === 1) {
            $lvl = array_shift($levels);
            return $trav[$lvl];
        } else {
            return $trav;
        }
    }
}
