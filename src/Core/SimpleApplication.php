<?php


namespace Parad0xeSimpleFramework\Core;


use Exception;
use Parad0xeSimpleFramework\Core\Request\Request;
use Parad0xeSimpleFramework\Core\Response\ErrorResponse;
use Parad0xeSimpleFramework\Core\Response\ResponseInterface;

class SimpleApplication extends Application
{
    /**
     * @var ResponseInterface
     */
    private ResponseInterface $response;

    /**
     * SimpleApplication constructor.
     * @param $root_project_directory
     * @param array $env
     */
    public function __construct($root_project_directory, array $env = [])
    {
        parent::__construct($root_project_directory);

        try {
            $this->response = $this->dispatch(new Request($this->getContext()->config()->get("app.id"), $env));
        } catch (Exception $e) {
            $this->response = new ErrorResponse($this->_context, 500, $e->getMessage());
        }
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
