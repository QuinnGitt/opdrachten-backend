<?php

 class Core
 {
    private $currentController = 'Homepage';
    private $currentMethod = 'index';
    private $params = [];

    public function __construct()
    {
        $url = $this->getURL();
        //  var_dump($url);exit();
        if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            $this->currentController = ucwords($url[0]);
            // echo $this->currentController;
            unset($url[0]);
            // var_dump($url);
        }
        // We sluiten het klasse-bestand in. 
        require_once '../app/controllers/'. $this->currentController . '.php';

        // We maken een nieuw object van de controller klasse
        $this->currentController = new $this->currentController();


        // We gaan kijken naar het tweede gedeelte van het array $url
        if (isset($url[1])) {
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
                // var_dump($url);
            }
        }

        
        $this->params = $url ? array_values($url): [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }


    public function getURL()
    {
        // var_dump($_GET['url']);exit();
        if (isset($_GET['url'])) {
            // We halen de forward slash van de url-tekst af
            $url = rtrim($_GET['url'], '/');

            $url = filter_var($url, FILTER_SANITIZE_URL);

            $url = explode('/', $url);

            return $url;
        } else {
            return array('Homepage', 'index');
        }
    }
 }