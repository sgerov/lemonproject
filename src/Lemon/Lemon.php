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
     * Bets Array
     *
     * An array containing all the best we are aiming to.
     */
    private $betsArray;

    /**
     * Constructor
     */
    public function __construct($env = NULL)
    {
        $this->container = new Container($env);
    }

    /**
     * Get Bets
     *
     * This method will obtain the recommended bets for our concrete filter.
     *
     * @param $bookmakers
     * @param $filters
     *
     * @return Array
     */
    public function getBets($bookmakers, $filters)
    {
        //TODO
        return array();
    }
}
