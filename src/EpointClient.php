<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EpointClient;

/**
 * Startup class
 */
class EpointClient
{
    /**
     * Initialize
     */
    public function __construct()
    {
        $this->checkConfig();
    }

    /**
     * Check configuration exists
     *
     * @return void
     */
    protected function checkConfig()
    {
        $this->checkEntryPoint();
        $this->checkDb();
        $this->checkStoreId();
        $this->checkUsername();
        $this->checkPassword();
    }

    /**
     * Check entry point exists
     *
     * @return void
     * @throws Exception
     */
    protected function checkEntryPoint()
    {
        if (defined('EPOINT_ENTRY_POINT')) {
            return true;
        }
        if (function_exists('env')) {
            if (null !== env('EPOINT_ENTRY_POINT')) {
                define('EPOINT_ENTRY_POINT', env('EPOINT_ENTRY_POINT'));
                return true;
            }
        }
        if (isset($_ENV['EPOINT_ENTRY_POINT'])) {
            define('EPOINT_ENTRY_POINT', $_ENV['EPOINT_ENTRY_POINT']);
            return true;
        }

        throw new \Exception("Missing configuration for Entry Point. Please check documentation.");
    }

    /**
     * Check db exists
     *
     * @return void
     * @throws Exception
     */
    protected function checkDb()
    {
        if (defined('EPOINT_DB')) {
            return true;
        }
        if (function_exists('env')) {
            if (null !== env('EPOINT_DB')) {
                define('EPOINT_DB', env('EPOINT_DB'));
                return true;
            }
        }
        if (isset($_ENV['EPOINT_DB'])) {
            define('EPOINT_DB', $_ENV['EPOINT_DB']);
            return true;
        }

        throw new \Exception("Missing configuration for DB. Please check documentation.");
    }

    /**
     * Check store id exists
     *
     * @return void
     * @throws Exception
     */
    protected function checkStoreId()
    {
        if (defined('EPOINT_STORE_ID')) {
            return true;
        }
        if (function_exists('env')) {
            if (null !== env('EPOINT_STORE_ID')) {
                define('EPOINT_STORE_ID', env('EPOINT_STORE_ID'));
                return true;
            }
        }
        if (isset($_ENV['EPOINT_STORE_ID'])) {
            define('EPOINT_STORE_ID', $_ENV['EPOINT_STORE_ID']);
            return true;
        }

        throw new \Exception("Missing configuration for Store ID. Please check documentation.");
    }

    /**
     * Check username exists
     *
     * @return void
     * @throws Exception
     */
    protected function checkUsername()
    {
        if (defined('EPOINT_USERNAME')) {
            return true;
        }
        if (function_exists('env')) {
            if (null !== env('EPOINT_USERNAME')) {
                define('EPOINT_USERNAME', env('EPOINT_USERNAME'));
                return true;
            }
        }
        if (isset($_ENV['EPOINT_USERNAME'])) {
            define('EPOINT_USERNAME', $_ENV['EPOINT_USERNAME']);
            return true;
        }

        throw new \Exception("Missing configuration for Username. Please check documentation.");
    }

    /**
     * Check password exists
     *
     * @return void
     * @throws Exception
     */
    protected function checkPassword()
    {
        if (defined('EPOINT_PASSWORD')) {
            return true;
        }
        if (function_exists('env')) {
            if (null !== env('EPOINT_PASSWORD')) {
                define('EPOINT_PASSWORD', env('EPOINT_PASSWORD'));
                return true;
            }
        }
        if (isset($_ENV['EPOINT_PASSWORD'])) {
            define('EPOINT_PASSWORD', $_ENV['EPOINT_PASSWORD']);
            return true;
        }

        throw new \Exception("Missing configuration for Password. Please check documentation.");
    }
}
