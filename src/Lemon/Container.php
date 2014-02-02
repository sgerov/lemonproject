<?php

/**
 * Container
 *
 * This container will handle all the project dependencies.
 *
 * @author Sava Gerov <sava@gerov.es>
 */

namespace Lemon;
use Symfony\Component\Yaml\Yaml;

class Container extends \Pimple
{
    /**
     * Environment filename
     *
     * Is the file name of the environment file.
     *
     * @var Const
     */
    const ENVIRONMENT_FILENAME = "environment.txt";

    /**
     * Config dir path
     *
     * Is the path where the configuration files are placed.
     *
     * @var String
     */
    protected $configDirPath;

    /**
     * Environment file path
     *
     * Is the place where the environment file is located.
     *
     * @var String
     */
    protected $environmentFilePath;

    /**
     * Construct
     *
     * @param String $environment
     */
    public function __construct($environment = NULL)
    {
        $this->configDirPath = dirname(__DIR__) . "/../config/";
        $this->environmentFilePath = $this->configDirPath
            . self::ENVIRONMENT_FILENAME;

        $this->setupEnvironment($environment);

        $this->addConfigDependencies();
        $this->addLogDependencies();
        $this->addAppDependencies();
    }

    /**
     * Setup environment
     *
     * Method that will setup the environment of the library.
     *
     * @param String $environment
     */
    public function setupEnvironment($environment)
    {
        if ($environment === NULL)
        {
            if (!is_readable($this->environmentFilePath))
            {
                throw new \Exception(
                    "Environment file not readable or not found in path: "
                    . "'$environmentFilePath'. Please, provide a environment "
                    . "via constructor parameter of Sherlock or via the "
                    . "standard file path: '" . $this->environmentFilePath . "'"
                );
            }

            $this["environment"] =
                trim(file_get_contents($this->environmentFilePath));
        }
        else
        {
            $this["environment"] = $environment;
        }
    }

    /**
     * Add config dependencies
     */
    private function addConfigDependencies()
    {
        $configFile = $this->configDirPath . $this["environment"] . ".yml";

        if (!is_readable($configFile)) {
            throw new \Exception(
                "Config file not readable or not found: '$configFile'."
            );
        }

        $this["config"] = Yaml::parse($configFile);
    }

    /**
     * Add log dependencies
     */
    private function addLogDependencies()
    {
        $logPath = $this['config']['log']['path'] . "/events.log";

        $this['Logger_class'] = '\Monolog\Logger';
        $this['Logger'] = $this->share(
            function ($c) use ($logPath) {
                $stream = new \Monolog\Handler\StreamHandler($logPath);

                $formatter = new \Monolog\Formatter\LineFormatter(
                    date('c') . "\t%message%\n"
                );
                $stream->setFormatter($formatter);

                $log = new $c['Logger_class']('Lemon');
                $log->pushHandler($stream);

                return $log;
            }
        );
    }


    /**
     * Add application dependencies
     */
    private function addAppDependencies()
    {
        // get bookmakers names
        $bookmakers = array_keys($this["config"]["bookmakers"]);
        $bookmakersArray = array();
        foreach ($bookmakers as $key => $bookmaker)
        {
            $type = "\Lemon\Parser\\$bookmaker\Parser";
            $bookmakersArray[$bookmaker] = new $type(
                $bookmaker,
                $this["config"]["bookmakers"]["$bookmaker"]["base"],
                $this["config"]["bookmakers"]["$bookmaker"]["odds"]
                );
        }
        $this['bookmakers'] = $bookmakersArray;
    }
}
