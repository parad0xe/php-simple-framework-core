<?php /** @noinspection PhpIncludeInspection */


namespace Parad0xeSimpleFramework\Core\Response;


class JsonResponse implements ResponseInterface
{
    /**
     * @var array
     */
    private array $_args;

    /**
     * Response constructor.
     * @param array $args
     */
    public function __construct(array $args = [])
    {
        $this->_args = $args;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return json_encode($this->_args);
    }
}
