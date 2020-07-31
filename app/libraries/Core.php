<?php
//
// App core Class
// Creates URL & loads core controller
// URL FORMAT - /controller/method/params
//

class Core
{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        //$this->getUrl();
        $url = $this->getUrl();

        if (isset($url[0])) {
            // Look in controllers for first value
            if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
                // If exists, set as controller
                $this->currentController = ucwords($url[0]);
                // Unset 0 Index - or just removes array 0 index data
                unset($url[0]);
            }
        }

        // Require the contrller
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // CHeck for second part of url
        if (isset($url[1])) {
            // Check to see if method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                // Unset 1 index
                unset($url[1]);
            }
        }

        // Get params (This will put all left params in array)
        $this->params = $url ? array_values($url) : [];

        // Call a callbackwith array of params 
        // This will make all $this->params as $this->currentMethod parameters
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl()
    {
        if (isset($_GET['url'])) {
            //this will trim "/" from ending
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // Put string to array by delimitting with "/"
            $url = explode('/', $url);
            return $url;
        }
    }
}
