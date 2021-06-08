<?php /** @noinspection PhpIncludeInspection */


namespace Parad0xeSimpleFramework\Core\Response;


class JsonResponse implements ResponseInterface
{
    /**
     * @var array
     */
    private array $args;

    /**
     * Response constructor.
     * @param array $args
     */
    public function __construct(array $args = [])
    {
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return json_encode($this->args);
    }
}
