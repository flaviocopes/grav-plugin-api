<?php
namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Common\User\User;

class ApiPlugin extends Plugin
{
    protected $route = 'api';

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPagesInitialized' => ['onPagesInitialized', 0],
        ];
    }

    public function onPagesInitialized()
    {
        $uri = $this->grav['uri'];

        if (strpos($uri->path(), $this->config->get('plugins.api.route') . '/' . $this->route) === false) {
            return;
        }

        if (!$this->isAuthorized()) {
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }

        $paths = $this->grav['uri']->paths();
        $paths = array_splice($paths, 1);
        $resource = $paths[0];

        if ($resource) {
            $file = __DIR__ . '/resources/' . $resource . '.php';
            if (file_exists($file)) {
                require_once $file;
                $resourceClassName = '\Grav\Plugin\Api\\' . ucfirst($resource);
                $resource = new $resourceClassName($this->grav);
                $output = $resource->execute();
                $resource->setHeaders();

                echo $output;
            } else {
                header('HTTP/1.1 404 Not Found');
            }
        }

        exit();
    }

    private function isAuthorized()
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            return false;
        }
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        $user = User::load($username);
        $isAuthenticated = $user->authenticate($password);

        if ($isAuthenticated) {
            if ($this->authorize($user, ['admin.api', 'admin.super'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks user authorisation to the action.
     *
     * @param  string $action
     *
     * @return bool
     */
    public function authorize($user, $action)
    {
        $action = (array)$action;

        foreach ($action as $a) {
            if ($user->authorize($a)) {
                return true;
            }
        }

        return false;
    }

}
