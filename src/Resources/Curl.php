<?php

/**
 * This file is part of the IskandarJamil/EpointClient package.
 *
 * (c) Iskandar Jamil
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EpointClient\Resources;

use EpointClient\Interfaces\CurlInterface;
use SimpleXMLElement;
use Exception;

class Curl implements CurlInterface
{
    protected $entryPoint = "";
    protected $headers = [];
    protected $query = "";
    protected $isPost = false;
    protected $sendAsPostRequest = false;
    protected $isXml = false;
    protected $token = "";
    protected $wrapper;
    protected $debug = false;

    public function __construct()
    {
        $this->headers['Content-Type'] = 'application/json';
        $this->headers['cache-control'] = 'no-cache';
    }

    public function validate()
    {
        if (empty($this->getEntryPoint())) {
            throw new Exception("Please provide url entry point.");
        }

        return $this;
    }

    public function init()
    {
        if ($this->isXml) {
            $xml = new SimpleXMLElement('<param/>');
            foreach (json_decode($this->query) as $key => $value) {
                $xml->addChild($key, $value);
            }
            $this->query = urlencode($xml->asXML());
        }

        return $this;
    }

    public function run()
    {
        $this->init();
        $this->validate();

        $ch = curl_init();

        $url = $this->getEntryPoint() . '?' . $this->getQuery();
        $query = $this->getQuery();

        if ($this->hasWrapper()) {
            $url = $this->getEntryPoint() . '?' . $this->getWrapper() .'='. $this->getQuery();
            $query = $this->getWrapper() . '='. $this->getQuery();
        }

        if ($this->isPost === true) {
            if ($this->sendAsPostRequest === true) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
            }
            curl_setopt($ch, CURLOPT_URL, $this->getEntryPoint());
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
            curl_setopt($ch, CURLOPT_URL, $url);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($this->hasDebug()) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $verbose = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);
        }

        $result = curl_exec($ch);
        curl_close($ch);

        if ($this->hasDebug()) {
            if ($result === false) {
                printf(
                    "cUrl error (#%d): %s<br>\n",
                    curl_errno($ch),
                    htmlspecialchars(curl_error($ch))
                );
            }

            rewind($verbose);
            $verboseLog = stream_get_contents($verbose);

            echo "<pre>" . $verboseLog . "</pre>";
        }


        $response = $result;

        if ($this->isXml) {
            $response = simplexml_load_string($response);
            $response = json_encode($response);
        }

        return $response;
    }

    public function url(String $url)
    {
        $this->entryPoint = $url;

        return $this;
    }

    public function data()
    {
        $args = func_get_args();

        $parameter = array_filter($args, function ($q) {
            return in_array(gettype($q), ['string', 'boolean', "integer"]);
        });
        $parameter = array_flip($parameter);


        if (isset($parameter['json'])) {
            $find_key = array_search('json', $args);
            if ($find_key) {
                unset($args[$find_key]);
            }
        }

        if (sizeof($args) > 0) {
            foreach ($args as $key => $arg) {
                if (is_array($arg)) {
                    foreach ($arg as $key => $value) {
                        $args[$key] = $value;
                    }
                    unset($args[0]);
                }
            }
        }

        if (isset($parameter['json'])) {
            $this->query = json_encode($args);
        } else {
            $this->query = is_array($args) ? urldecode(http_build_query($args)) : $args;
        }


        return $this;
    }

    public function wrapper(String $value)
    {
        $this->wrapper = $value;

        return $this;
    }

    public function token(String $token)
    {
        $this->token = $token;

        return $this;
    }

    public function cacheable()
    {
        unset($this->headers['cache-control']);

        return $this;
    }

    public function debug()
    {
        $this->debug = true;

        return $this;
    }

    public function isPost()
    {
        $this->isPost = true;

        return $this;
    }

    public function isXml()
    {
        $this->headers['Content-Type'] = 'application/xml';
        $this->isXml = true;

        return $this;
    }

    public function isSendAsPostRequest()
    {
        $this->sendAsPostRequest = true;

        return $this;
    }

    public function hasToken()
    {
        $this->addHeader('Authorization', 'Bearer ' . $this->getToken());

        return $this;
    }

    public function hasWrapper()
    {
        return $this->wrapper !== null;
    }

    public function hasDebug()
    {
        return $this->debug === true;
    }

    public function getEntryPoint()
    {
        return $this->entryPoint;
    }

    public function getQuery()
    {
        $query = $this->query;

        for ($i = 0; $i < 100; $i++) {
            $query = str_replace("[{$i}]", "[]", $query);
        }

        return $query;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getWrapper()
    {
        return $this->wrapper;
    }

    public function getHeaders()
    {
        $curl_header = array();
        array_map(function ($k, $q) use (&$curl_header) {
            $curl_header[] = "{$k}: $q";
        }, array_keys($this->headers), $this->headers);

        return $curl_header;
    }

    public function addHeader(String $key, $value = '')
    {
        $this->headers['Content-Type'] = 'application/xml';

        return $this;
    }
}
