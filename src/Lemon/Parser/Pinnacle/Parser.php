<?php

/**
 * Pinnacle
 *
 * Pinnaclesports.com specific parser.
 */

namespace Lemon\Parser\Pinnacle;
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
            $participants = $odds = array();
            return array(
                $sport
                =>
                array_filter($request->filter('events event')->each(function ($event) {
                    $participants = $event->filter('participants participant')->each( function ($participant) {
                        return $participant->filter('participant_name')->text();
                    });

                    $odd = $event->filter('periods period moneyline');
                    if (count($odd))
                    {
                        $odds[0] = $odd->filter('moneyline_home')->text();
                        $odds[1] = $odd->filter('moneyline_visiting')->text();
                    }
                    if(count($odd) && $participants[0] && $participants[1])
                    {
                        return array(
                            $this->convertOdds($odds[0], "us"),
                            $participants[0],
                            $this->convertOdds($odds[1], "us"),
                            $participants[1]
                        );
                    }
                    else
                    {
                        return false;
                    }
                }))
            );
        }
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
        //TODO
    }
}
