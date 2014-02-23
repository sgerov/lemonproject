<?php

/**
 * Betfair
 *
 * Betfair.es specific parser.
 */

namespace Lemon\Parser\Betfair;
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
                array_filter($request->filter('tbody.market')->each(function ($threeRow) {
                    $home = $threeRow->filter('span.home-team');
                    $away = $threeRow->filter('span.away-team');
                    $homeBet = $threeRow->filter('td.selection-1 button span')->first();
                    $awayBet = $threeRow->filter('td.selection-2 button span')->first();
                    if(count($homeBet) && count($awayBet) && count($home) && count($away))
                    {
                        return array(
                            (float) $homeBet->html(),
                            $home->html(),
                            (float) $awayBet->html(),
                            $away->html()
                        );
                    }
                }));
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
        //TODO
    }
}
