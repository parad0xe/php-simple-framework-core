<?php


namespace Parad0xeFramework\Core;


use Exception;
use Parad0xeSimpleFramework\Core\Application;
use Parad0xeSimpleFramework\Core\Request\Request;
use Parad0xeSimpleFramework\Core\Response\ResponseInterface;

class SimpleApplication extends Application
{
    /**
     * @var ResponseInterface
     */
    private ResponseInterface $response;

    public function __construct($root_project_directory)
    {
        parent::__construct($root_project_directory);

        try {
            $this->response = $this->dispatch(new Request($_POST, $_GET));
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
