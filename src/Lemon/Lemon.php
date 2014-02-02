<?php

/**
 * Lemon
 *
 * This is the class that provides the safe bets we should play on.
 *
 * @author Sava Gerov <sava@gerov.es>
 */

namespace Lemon;

class Lemon
{
    /**
     * Container
     *
     * All objects are handled through this Pimple container.
     */
    private $container;

    /**
     * Odds Array
     *
     * An array containing all the requested odds.
     */
    private $oddsArray;

    /**
     * Constructor
     */
    public function __construct($env = NULL)
    {
        $this->container = new Container($env);
        $this->oddsArray = array();
    }

    /**
     * Get Odds
     *
     * Populate the array with all the odds of all bookmakers.
     *
     * @param Array $selectedBookmakers Bookmakers we want to filter by
     * @param Array $filters Doubles, triples or specific filters
     */
    public function getOdds($selectedBookmakers = NULL, $filters = NULL)
    {
        $bookmakers = $this->container['bookmakers'];

        foreach ($selectedBookmakers as $bookmakerName) {
            if (
                array_key_exists(
                    $bookmakerName,
                    $this->container['config']['bookmakers']
                )
            )
            {
                $this->container["Logger"]->info("Getting '$bookmakerName' odds.");
                switch ($filters) {
                    case 'three':
                        $this->oddsArray[$bookmakerName]["three"] = $bookmakers[$bookmakerName]->getTriples();
                        break;
                    case 'two':
                        $this->oddsArray[$bookmakerName]["two"] = $bookmakers[$bookmakerName]->getDoubles();
                        break;
                    default:
                        $this->oddsArray[$bookmakerName]["two"] = $bookmakers[$bookmakerName]->getDoubles();
                        break;
                }
                $this->container["Logger"]->info("'$bookmakerName' odds obtained.");
                var_dump($this->oddsArray[$bookmakerName]["two"]); die;
            }
        }
    }
}
