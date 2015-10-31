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
        $pagesCollection = $this->grav['pages']->all();

        $return = [];

        foreach($pagesCollection as $page) {
            $return[$page->route()] = [];
            $return[$page->route()]['title'] = $page->title();
            $return[$page->route()]['url'] = $page->url();
            $return[$page->route()]['visible'] = $page->visible();
            $return[$page->route()]['isDir'] = $page->isDir();
            $return[$page->route()]['published'] = $page->published();
        }

        return $return;
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
        $pages = $this->grav['pages'];
        $page = $pages->dispatch('/' . $this->getIdentifier(), false);
        return $this->buildPageStructure($page);
    }

    private function buildPageStructure($page) {
        return [
            'active' => $page->active(),
            'activeChild' => $page->activeChild(),
            // 'adjacentSibling' => $page->adjacentSibling(),
            'blueprintName' => $page->blueprintName(),
            //'blueprints' => $page->blueprints(),
            'children' => $page->children(),
            'childType' => $page->childType(),
            'content' => $page->content(),
            'date' => $page->date(),
            'eTag' => $page->eTag(),
            'expires' => $page->expires(),
            'exists' => $page->exists(),
            'extension' => $page->extension(),
           // 'extra' => $page->extra(),
            'file' => $page->file(),
            'filePath' => $page->filePath(),
            'filePathClean' => $page->filePathClean(),
            'folder' => $page->folder(),
            'frontmatter' => $page->frontmatter(),
            'getRawContent' => $page->getRawContent(),
            'header' => $page->header(),
            'home' => $page->home(),
            'id' => $page->id(),
            'isDir' => $page->isDir(),
            // 'isFirst' => $page->isFirst(),
            // 'isLast' => $page->isLast(),
            'isPage' => $page->isPage(),
            'language' => $page->language(),
            'lastModified' => $page->lastModified(),
            'link' => $page->link(),
            'maxCount' => $page->maxCount(),
            'menu' => $page->menu(),
            'metadata' => $page->metadata(),
            'modified' => $page->modified(),
            'modularTwig' => $page->modularTwig(),
            'modular' => $page->modular(),
            'name' => $page->name(),
            // 'nextSibling' => $page->nextSibling(),
            'order' => $page->order(),
            'orderDir' => $page->orderDir(),
            'orderBy' => $page->orderBy(),
            'orderManual' => $page->orderManual(),
            'parent' => $page->parent(),
            'path' => $page->path(),
            'permalink' => $page->permalink(),
            // 'prevSibling' => $page->prevSibling(),
            'publishDate' => $page->publishDate(),
            'published' => $page->published(),
            'raw' => $page->raw(),
            'rawMarkdown' => $page->rawMarkdown(),
            'rawRoute' => $page->rawRoute(),
            'root' => $page->root(),
            'routable' => $page->routable(),
            'route' => $page->route(),
            'routeCanonical' => $page->routeCanonical(),
            'slug' => $page->slug(),
            'summary' => $page->summary(),
            'taxonomy' => $page->taxonomy(),
            'template' => $page->template(),
            'title' => $page->title(),
            'translatedLanguages' => $page->translatedLanguages(),
            'unpublishDate' => $page->unpublishDate(),
            'untranslatedLanguages' => $page->untranslatedLanguages(),
            'url' => $page->url(),
            'visible' => $page->visible(),
        ];
    }
}



















