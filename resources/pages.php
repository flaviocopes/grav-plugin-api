<?php
namespace Grav\Plugin\Api;
require_once 'resource.php';

/**
 * Pages API
 *
 * @todo: add POST action to add a new page
 * @todo: add PUT action to edit an existing page
 * @todo: add DELETE action to delete an existing page
 */
class Pages extends Resource
{
    /**
     * Get the pages list
     *
     * @todo: return an array, not an object
     *
     * @return array the pages list
     */
    public function getList()
    {
        $this->grav['pages']->init();
        return $this->grav['pages']->all()->toArray();
    }

    /**
     * Get a single page
     *
     * @todo: return more information
     *
     * @return array the single page
     */
    public function getItem()
    {
        $this->grav['pages']->init();
        $pages = $this->grav['pages'];
        $item = $pages->dispatch('/' . $this->getIdentifier(), false);
        return $item->toArray();
    }
}
