<?php

/**
 * Parser Interface
 *
 * @author Sava Gerov <sava@gerov.es>
 */

namespace Lemon\Parser;

interface ParserInterface
{
    /**
     * Get Doubles
     *
     * Method that will obtain all two-outcome bets in TSV format.
     */
    public function getDoubles();

    /**
     * Get Triples
     *
     * Method that will obtain all three-outcome bets in TSV format.
     */
    public function getTriples();
}
