<?php
/**
 * Class ClientRPCjson
 * @author Lukasz Narloch lukassz111@gmail.com
 * @copyright Lukasz Narloch lukassz111@gmail.com
 * @license WTFPL
 * @since 1.0
 */
class ClientRPCjson
{
    private $viewRaw = false;
    private $sessionId = -1;
    private $url = null;
    public function __construct($_url,$viewRaw = false)
    {
        $this->url = $_url;
        $this->viewRaw = $viewRaw;
    }
    public function __call($name, $arguments)
    {
        $data = array(
            'type'=>'function',
            'method'=>$name,
            'args'=>$arguments
        );
        return $this->send($data);
    }

    private function send($data)
    {
        $data['sessionId'] = $this->sessionId;

        $opt = array(
            'http' => array(
                // http://www.php.net/manual/en/context.http.php
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => json_encode($data)
            )
        );
        $context = stream_context_create($opt);
        $responseJson = file_get_contents($this->url,false,$context);
        $response = json_decode($responseJson,true);
        $this->sessionId = $response['sessionId'];
        if($this->viewRaw)
        {
            return $responseJson;
        }
        return $response['result'];
    }
}
?>