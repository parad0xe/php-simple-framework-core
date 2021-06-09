<?php


namespace Parad0xeSimpleFramework\Core\Auth;


use Parad0xeSimpleFramework\Core\Request\Request;

class Auth
{
    /**
     * @var Request
     */
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function isAuth(): bool
    {
        return $this->request->session()->has("user");
    }

    /**
     * @return mixed
     */
    public function user() {
        return ($this->isAuth()) ? $this->request->session()->get("user") : null;
    }

    /**
     * @param mixed $user
     */
    public function login($user) {
        $this->request->session()->set("user", $user);
    }

    public function logout()
    {
        $this->request->session()->unset("user");
    }

    /**
     * @param $password
     * @param $hash
     * @return bool
     */
    public function passwordCheck($password, $hash): bool {
        return hash("sha256", $password) === $hash;
    }

    /**
     * @param $password
     * @return string
     */
    public function hashPassword($password): string {
        return hash("sha256", $password);
    }
}
