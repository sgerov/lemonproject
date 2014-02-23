<?php

/**
 * Bwin
 *
 * Bwin.com specific parser.
 */

namespace Lemon\Parser\Bwin;
use Lemon\Parser\ParserAbstract;
use Lemon\Parser\ParserInterface;

class Parser
    extends ParserAbstract
    implements ParserInterface
{

    /**
     * Get Doubles
     *
     * Function that will return an array containing all the sports that
     * have two possible outcomes.
     *
     * @return Array
     */
    public function getDoubles()
    {
        foreach ($this->webRequests["two"] as $sport => $request)
        {
            $return[$sport] =
                $request->filter('tr.colx ')->each(function ($threeRow) {
                    return $threeRow->filter('td form button span')
                        ->each(function ($one) {
                            return $one->text();
                    });
                });
        }
        return $return;
    }

    /**
     * Get Triples
     *
     * Function that will return an array containing all the sports that
     * have three possible outcomes.
     *
     * @return Array
     */
    public function getTriples()
    {
        foreach ($this->webRequests["three"] as $sport => $request)
        {
            return array(
                $sport
                =>
                $request->filter('.col3.three-way')->each(function ($threeRow) {
                    return $threeRow->filter('td form button span')
                            ->each(function ($one) {
                                return $one->text();
                    });
                })
            );
        }
    }
}
