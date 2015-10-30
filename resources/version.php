<?php
namespace Grav\Plugin\Api;
require_once 'resource.php';

/**
 * Version API
 */
class Version extends Resource
{
    /**
     *
     */
    public function getList()
    {
        return '0';
    }
    /**
     *
     */
    public function getItem()
    {
        switch($this->getIdentifier()) {
            case 'major':
                return '0';
            case 'minor':
                return '0.1';
            case 'full':
                return '0.1.0';
        }

        return;
    }
}
