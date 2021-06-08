<?php /** @noinspection PhpIncludeInspection */


namespace Parad0xeSimpleFramework\Core\Response;


use Parad0xeSimpleFramework\Core\ApplicationContext;

class EmptyResponse implements ResponseInterface
{
    /**
     * Response constructor.
     */
    public function __construct()
    {}

    /**
     * @return string
     */
    public function render(): string
    {
        return "";
    }
}
