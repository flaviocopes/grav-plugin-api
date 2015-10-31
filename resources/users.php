<?php
namespace Grav\Plugin\Api;
require_once 'resource.php';

use Symfony\Component\Yaml\Yaml;

/**
 * Users API
 *
 * @todo: add POST action to add a new user
 * @todo: add PUT action to edit an existing user
 * @todo: add DELETE action to delete an existing user
 */
class Users extends Resource
{
    /**
     * Get the users list
     *
     * Implements:
     *
     * - GET /api/users
     *
     * @todo:
     *
     * @return array the users list
     */
    public function getList()
    {
        $users = [];
        $files = (array) glob($this->grav['locator']->findResource("account://") . '/*.yaml');

        if ($files) foreach ($files as $file) {
            $users[] = array_merge(array('username' => basename($file, '.yaml')), Yaml::parse($file));
        }

        return $users;
    }

    /**
     * Get a single user
     *
     * Implements:
     *
     * - GET /api/users/:user
     *
     * @todo:
     *
     * @return array the single user
     */
    public function getItem()
    {
        $file = $this->grav['locator']->findResource("account://") . '/' . $this->getIdentifier() . '.yaml';
        if (!file_exists($file)) {
            $this->setErrorCode(404);
            return;
        }
        return array_merge(array('username' => basename($file, '.yaml')), Yaml::parse($file));
    }
}
