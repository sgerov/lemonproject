<?php

/**
 * Parser Abstract
 *
 * @author Sava Gerov <sava@gerov.es>
 */

namespace Lemon\Parser;
use Goutte\Client;

abstract class ParserAbstract
{
    /**
     * Name
     *
     * Name of the bookmaker.
     *
     * @var String
     */
    protected $name;

    /**
     * Url
     *
     * Base url of the bookmaker.
     *
     * @var String
     */
    protected $baseUrl;

    /**
     * Uri List
     *
     * A list of URIs which we will be analyzed.
     *
     * @var Array
     */
    protected $uriList;

    /**
     * Client
     *
     * Goutte client instance.
     *
     * @var Goutte\Client
     */
    protected $client;

    /**
     * Web Requests
     *
     * All requests we will do for a given bookmaker.
     *
     * @var Array
     */
    protected $webRequests;

    /**
     * Constructor
     *
     * @param $name Name of the bookmaker
     * @param $baseUrl Bookmaker base url
     * @param $uriList List of sports and URIs from which we will obtain odds
     */
    function __construct($name, $baseUrl, $uriList)
    {
        $this->name = $name;
        $this->baseUrl = $baseUrl;
        $this->client = (new \Goutte\Client);
        $this->webRequests = array();
        $this->requestOdds($uriList);
    }

    /**
     * Getter
     */
    public function __get($property)
    {
        if (property_exists($this, $property))
        {
          return $this->$property;
        }
    }

    /**
     * Request Odds
     *
     * Function that will download the necessary web pages in order to retrieve
     * the odds
     */
    public function requestOdds($uriList)
    {
        // get all two-outcome odds
        foreach ($uriList['two'] as $sport => $uri)
        {
            $this->webRequests['two'][$sport] = $this->client->request(
                'GET',
                $this->baseUrl . $uri
            );
        }

        // get all three-outcome odds
        foreach ($uriList['three'] as $sport => $uri)
        {
            $this->webRequests['three'][$sport] = $this->client->request(
                'GET',
                $this->baseUrl . $uri
            );
        }
    }

    /**
     * Convert Odds
     *
     * Odds are represented in different way across bookmakers. This method
     * will help to translate those to the common decimal format.
     *
     * @param $odds Float
     * @param $format String
     *
     * @return Float
     */
    protected function convertOdds($odds, $format)
    {
        switch ($format)
        {
            case 'us':
                if ($odds <= -100)
                {
                    $returnValue = 1 - (100 / $odds);
                }
                else
                {
                    $returnValue = 1 + ($odds / 100);
                }
                break;

            default:
                    $returnValue = NULL;
                break;
        }

        return $returnValue;
    }
}
