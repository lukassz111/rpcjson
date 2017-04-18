<?php

/**
 * Class ServerRPCjson
 * @author Lukasz Narloch lukassz111@gmail.com
 * @copyright Lukasz Narloch lukassz111@gmail.com
 * @license WTFPL
 * @since 1.0
 */
    class ServerRPCjson
    {
        private $sessions = true;
        private $sessionDir = './session';
        private $connectedSession = null;
        private $object = null;
        public function __call($name, $arguments)
        {
            $countArgs = count($arguments);
            switch ($countArgs)
            {
                /**
                 * If you need to use more parametr
                 * You can add case to this switch.
                 **/
                case 0://For voids
                    return $this->object->$name();
                    break;
                case 1://For one parametrs
                    return $this->object->$name($arguments[0]);
                    break;
                case 2://etc
                    return $this->object->$name($arguments[0],$arguments[1]);
                    break;

                default:
                    throw new Exception("Add a case to switch with more arguments in ".basename(__FILE__));
                    break;
            }
        }

        public function __construct($_object,$sessions = false)
        {
            $this->sessions = $sessions;
            file_put_contents('log.txt', "Start".PHP_EOL, FILE_APPEND);
            $this->object = $_object;
            header("Content-Type: text/plain");
            $post = file_get_contents('php://input');
            if($post=="")
            {
                return;
            }
            file_put_contents('log.txt', $post.PHP_EOL, FILE_APPEND);
            $data = json_decode($post);
            if($this->sessions == false) {}
            elseif($data->sessionId == -1 && $this->sessions == true)
            {
                do
                {
                    $this->connectedSession = $this->getRandomSessionId();
                }while($this->isSessionExist($this->connectedSession));
                $this->saveSession();
            }
            else
            {
                $this->connectedSession = $data->sessionId;
                $this->loadSession();
            }
            $return = array();
            switch ($data->type)
            {
                case "function":
                    file_put_contents('log.txt', "FUNCTION:".PHP_EOL, FILE_APPEND);
                    $arguments = $data->args;
                    $method = $data->method;
                    try
                    {
                        $result = $this->__call($method, $arguments);
                        $return['result'] = $result;
                        //echo json_encode($result);
                    }
                    catch (Exception $e)
                    {
                        echo json_encode("RPC Error: ".$e->getMessage());
                        $return;
                    }
                    break;
                default:
                    throw new Exception("Undefined type of RPC");
                    break;
            }
            if($this->sessions)
            {
                $this->saveSession();
            }
            $return['sessionId'] = $this->connectedSession;
            echo json_encode($return);
        }
        private function getRandomSessionId()
        {
            return substr(sha1(uniqid()).md5(uniqid()),0,10);
        }
        private function isSessionExist($sessionId)
        {
            $file = $this->sessionDir.'/'.$sessionId.'.txt';
            if(!file_exists($this->sessionDir))
                mkdir($this->sessionDir,0777,true);
            return file_exists($file);
        }
        private function loadSession()
        {
            $sessionId = $this->connectedSession;
            $file = $this->sessionDir.'/'.$sessionId.'.txt';
            if(!file_exists($this->sessionDir))
                mkdir($this->sessionDir,0777,true);
            if(file_exists($file))
            {
                $serialized = file_get_contents($file);
                $this->object = unserialize($serialized);
            }
            else
            {
                throw new Exception("Session in't exist!");
            }
        }
        private function saveSession()
        {
            $sessionId = $this->connectedSession;
            $file = $this->sessionDir.'/'.$sessionId.'.txt';
            if(!file_exists($this->sessionDir))
                mkdir($this->sessionDir,0777,true);

            $serialized = serialize($this->object);
            file_put_contents($file,$serialized);
        }
    }
?>