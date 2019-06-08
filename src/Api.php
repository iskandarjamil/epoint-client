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

use EpointClient\Api\ApiRegisterCard;
use EpointClient\Interfaces\ServiceInterface;
use EpointClient\Repositories\RequestRepository;
use EpointClient\Resources\IsDateTimeAble;
use EpointClient\Resources\Request;
use EpointClient\Resources\Respond;

class Api
{
    use IsDateTimeAble;

    protected $request;
    protected $respond;
    protected $tasks;

    public function __construct()
    {
        $this->request = new Request($_REQUEST);
        $this->respond = new Respond();
    }

    public function init()
    {
        if ($this->request->has('action')) {
            $task = $this->tasks();

            if (!is_null($task)) {
                $task->handle($this->request)
                    ->then(function ($response) {
                        $this->output($response);
                    })
                    ->reject(function ($response) {
                        $this->respond->error($response);
                    });
            }
        }
    }

    public function tasks()
    {
        $task = null;
        $action = $this->request->get('action');

        if ($this->isTaskExists($action)) {
            $task = $this->getTask($action);
        }

        return $task;
    }

    public function register(String $key, $implementation)
    {
        if ($this->isTaskExists($key)) {
            throw new \Exception("Task already exists, please use other name.");
        }

        if (is_string($implementation)) {
            $instance = new $implementation();
        } else {
            $instance = $implementation;
        }

        if (!$this->isValidImplementation($instance)) {
            throw new \Exception("Not a valid task class.");
        }

        $this->tasks[$key] = $instance;
    }

    public function output($args)
    {
        $this->respond->success([
            'data' => isset($args['data']) ? $args['data'] : null,
            'message' => isset($args['message']) ? $args['message'] : null,
        ]);
    }

    public function isValidImplementation(ServiceInterface $implementation)
    {
        return true;
    }

    public function isTaskExists(String $key)
    {
        return isset($this->tasks[$key]);
    }

    public function getAllTasks()
    {
        return $this->tasks;
    }

    public function getTask(String $key)
    {
        return $this->tasks[$key];
    }
}
