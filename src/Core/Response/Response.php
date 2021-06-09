<?php /** @noinspection PhpIncludeInspection */


namespace Parad0xeSimpleFramework\Core\Response;


use Parad0xeSimpleFramework\Core\ApplicationContext;

class Response implements ResponseInterface
{
    private ApplicationContext $context;

    private ?string $page;

    private array $args;

    private string $page_directory;

    /**
     * Response constructor.
     * @param ApplicationContext $context
     * @param string $page
     * @param array $args
     */
    public function __construct(ApplicationContext $context, ?string $page, array $args = [])
    {
        $this->context = $context;
        $this->page = ($page) ? ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $page)), '_') : null;
        $this->args = $args;
        $this->page_directory = $this->context->config()->get('app.directory.pages');
    }

    /**
     * @return string
     */
    public function render(): string
    {
        if(!file_exists("{$this->page_directory}/{$this->page}.php")) {
            return $this->__load("errors/404");
        }

        return $this->__load($this->page);
    }

    /**
     * @param string $page
     * @return string
     */
    private function __load(string $page): string {
        extract($this->args);
        $context = $this->context;

        if(!file_exists("{$this->page_directory}/{$page}.php"))
            return "";

        ob_start();
        include("{$this->page_directory}/{$page}.php");
        return ob_get_clean();
    }
}
