<?php


namespace Parad0xeSimpleFramework\Core\Request;

use Parad0xeSimpleFramework\Core\Request\Bag\Cookie;
use Parad0xeSimpleFramework\Core\Request\Bag\Files;
use Parad0xeSimpleFramework\Core\Request\Bag\Flash;
use Parad0xeSimpleFramework\Core\Request\Bag\Get;
use Parad0xeSimpleFramework\Core\Request\Bag\Post;
use Parad0xeSimpleFramework\Core\Request\Bag\Server;
use Parad0xeSimpleFramework\Core\Request\Bag\Session;

trait RequestTrait
{
    protected string $_app_id;

    protected Server $_server;
    protected Post $_post;
    protected Get $_get;
    protected Session $_session;
    protected Flash $_flash;
    protected Files $_files;
    protected Cookie $_cookie;

    public function __construct(string $app_id, array $env = [])
    {
        $this->_app_id = $app_id;

        $server = $env["SERVER"] ?? $_SERVER;
        $post = $env["POST"] ?? $_POST;
        $get = $env["GET"] ?? $_GET;
        $cookie = $env["COOKIE"] ?? $_GET;

        $this->_server = new Server($server);
        $this->_post = new Post($post);
        $this->_get = new Get($get);
        $this->_session = new Session($app_id, $_SESSION);
        $this->_flash = new Flash($app_id, $_SESSION);
        $this->_files = new Files($_FILES);
        $this->_cookie = new Cookie($app_id, $cookie);
    }

    public function post(): Post
    {
        return $this->_post;
    }
    public function server(): Server
    {
        return $this->_server;
    }
    public function get(): Get {
        return $this->_get;
    }
    public function session(): Session {
        return $this->_session;
    }
    public function flash(): Flash
    {
        return $this->_flash;
    }
    public function files(): Files {
        return $this->_files;
    }
    public function cookie(): Cookie {
        return $this->_cookie;
    }
}
