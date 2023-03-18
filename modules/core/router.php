<?php

namespace Core;

final class Router
{
    private $rules;
    private $requestPath;
    private $requestMethod;

    private $controllerName;
    private $actionName;

    public function __construct(array $rules, string $requestPath)
    {
        $this->rules = $rules;
        $this->requestPath = $requestPath;
    }

    /**
     * this method define controllerName and actionName by params from url patterns and request methods
     */
    private function findController() 
    {
        $actionParams = array();
        $formArray = array();
        foreach($this->rules as $url => $params) {
            if(preg_match($url, $this->requestPath, $result) === 1) {
                if (isset($_SERVER['REQUEST_METHOD']) && isset($params['requestMethod']) && $_SERVER['REQUEST_METHOD'] == $params['requestMethod']) {
                    $this->controllerName = $params['controller'].'Form';
                    $this->actionName = $params['action'];
                    if(isset($params['params'])) {
                        for ($i = 0; $i < count($params['params']); $i++) {
                            $actionParams[$params['params'][$i]] = $result[$i+1];
                        }
                        $formArray = ['params' => $actionParams];
                    }
                    $formArray['formData'] = $_POST;
                    return $formArray;
                }
                $this->controllerName = $params['controller'];
                $this->actionName = $params['action'];
                if(isset($params['params'])) {
                    for ($i = 0; $i < count($params['params']); $i++) {
                        $actionParams[$params['params'][$i]] = $result[$i+1];
                    }
                    return $actionParams;
                } 
            }
        }
    }

    public function createController() 
    {
        $pregMatchResult = $this->findController();
        $controllerName = $this->getControllerName();
        $actionName = $this->getActionName();
        if($controllerName !== '' && $actionName) {
            $controller = new $controllerName;
            $controller->$actionName($pregMatchResult);
        } else {
            throw new \Exception\Page404Exception;
        }
    }

    private function getControllerName()
    {
        if($this->controllerName !== '') {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
                return '\FormController\\'.$this->controllerName.'Controller';
            }
            return '\Controller\\'.$this->controllerName.'Controller';
        } else {
            return '';
        }
    }

    private function getActionName()
    {
        return $this->actionName;
    }
}

?>