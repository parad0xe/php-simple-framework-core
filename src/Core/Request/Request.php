<?php
namespace Parad0xeSimpleFramework\Core\Request;

use Parad0xeSimpleFramework\Core\Request\Bag\Cookie;
use Parad0xeSimpleFramework\Core\Request\Bag\Files;
use Parad0xeSimpleFramework\Core\Request\Bag\Flash;
use Parad0xeSimpleFramework\Core\Request\Bag\Get;
use Parad0xeSimpleFramework\Core\Request\Bag\Post;
use Parad0xeSimpleFramework\Core\Request\Bag\Server;
use Parad0xeSimpleFramework\Core\Request\Bag\Session;

class Request
{
    /**
     * @var Server
     */
    private Server $_server;

    /**
     * @var Post
     */
    private Post $_post;

    /**
     * @var Get
     */
    private Get $_get;

    /**
     * @var Session
     */
    private Session $_session;

    /**
     * @var Flash
     */
    private Flash $_flash;

    /**
     * @var Files
     */
    private Files $_files;

    /**
     * @var Cookie
     */
    private Cookie $_cookie;

    public function __construct(array $post, array $get)
    {
        $this->_server = new Server($_SERVER);
        $this->_post = new Post($post);
        $this->_get = new Get($get);
        $this->_session = new Session($_SESSION);
        $this->_flash = new Flash($_SESSION);
        $this->_files = new Files($_FILES);
        $this->_cookie = new Cookie($_COOKIE);
    }

    /**
     * @return Post
     */
    public function post(): Post
    {
        return $this->_post;
    }

    /**
     * @return Server
     */
    public function server(): Server
    {
        return $this->_server;
    }

    /**
     * @return Get
     */
    public function get(): Get {
        return $this->_get;
    }

    /**
     * @return Session
     */
    public function session(): Session {
        return $this->_session;
    }

    /**
     * @return Flash
     */
    public function flash(): Flash
    {
        return $this->_flash;
    }

    /**
     * @return Files
     */
    public function files(): Files {
        return $this->_files;
    }

    /**
     * @return Cookie
     */
    public function cookie(): Cookie {
        return $this->_cookie;
    }
}
