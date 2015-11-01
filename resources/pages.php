<?php
namespace Grav\Plugin\Api;
require_once 'resource.php';

use Grav\Common\Filesystem\Folder;
use Grav\Common\Page\Page;
use RocketTheme\Toolbox\File\File;
use Symfony\Component\Yaml\Yaml;

/**
 * Pages API
 */
class Pages extends Resource
{
    /**
     * Get the pages list
     *
     * Implements:
     *
     * - GET /api/pages
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
     * Implements:
     *
     * - GET /api/pages/:page
     *
     * @return array the single page
     */
    public function getItem()
    {
        $pages = $this->grav['pages'];
        $page = $pages->dispatch('/' . $this->getIdentifier(), false);
        return $this->buildPageStructure($page);
    }

    /**
     * Create a new page
     *
     * Implements:
     *
     * - POST /api/pages/:page
     *
     * @todo:
     *
     * @return array the single page
     */
    public function postItem()
    {
        $data = $this->getPost();
        $pages = $this->grav['pages'];

        $page = $pages->dispatch('/' . $this->getIdentifier(), false);
        if ($page !== null) {
            // Page already exists
            $this->setErrorCode(403);
            $message = $this->buildReturnMessage('Page already exists. Cannot create a page with the same route');
            return $message;
        }

        $page = $this->page($this->getIdentifier());
        $page = $this->preparePage($page, $data);

        $page->save();

        $page = $pages->dispatch('/' . $this->getIdentifier(), false);

        return $this->buildPageStructure($page);
    }

    /**
     * Updates an existing page
     *
     * Implements:
     *
     * - PUT /api/pages/:page
     *
     * @todo:
     *
     * @return array the single page
     */
    public function putItem()
    {
        $data = $this->getPost();
        $pages = $this->grav['pages'];

        $page = $pages->dispatch('/' . $this->getIdentifier(), false);
        if ($page == null) {
            // Page does not exist
            $this->setErrorCode(404);
            $message = $this->buildReturnMessage('Page does not exist.');
            return $message;
        }

        $page = $this->page($this->getIdentifier());
        $page = $this->preparePage($page, $data);
        $page->save();

        $page = $pages->dispatch('/' . $this->getIdentifier(), false);

        return $this->buildPageStructure($page);
    }

    /**
     * Deletes an existing page
     *
     * If the 'lang' param is present in the request header, it only deletes a single
     * language if there are other languages set for that page.
     *
     * Implements:
     *
     * - DELETE /api/pages/:page
     *
     * @todo:
     *
     * @return bool
     */
    public function deleteItem()
    {
        $data = $this->getPost();

        $pages = $this->grav['pages'];
        $page = $pages->dispatch('/' . $this->getIdentifier(), false);
        if ($page == null) {
            // Page does not exist
            $this->setErrorCode(404);
            $message = $this->buildReturnMessage('Page does not exist.');
            return $message;
        }

        try {
            if (isset($data->lang) && count($page->translatedLanguages()) > 1) {
                $language = trim(basename($page->extension(), 'md'), '.') ?: null;
                $filename = str_replace($language, $data->lang, $page->name());
                $path = $page->path() . DS . $filename;
                $page->filePath($path);

                $page->file()->delete();
            } else {
                Folder::delete($page->path());
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Deleting page failed on error: ' . $e->getMessage());
        }

        return '';
    }

    /**
     * Build a page structure
     *
     * @todo: add commented fields
     *
     * @return array the single page
     */
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

    /**
     * Returns edited page.
     *
     * @param bool $route
     *
     * @return Page
     */
    private function page($route = false)
    {
        $path = $route;
        if (!isset($this->pages[$path])) {
            $this->pages[$path] = $this->getPage($path);
        }
        return $this->pages[$path];
    }

    /**
     * Returns the page creating it if it does not exist.
     *
     * @todo: Copied from Admin Plugin. Refactor to use in both
     *
     * @param $path
     *
     * @return Page
     */
    private function getPage($path)
    {
        /** @var Pages $pages */
        $pages = $this->grav['pages'];

        if ($path && $path[0] != '/') {
            $path = "/{$path}";
        }

        $page = $path ? $pages->dispatch($path, true) : $pages->root();

        if (!$page) {
            $slug = basename($path);

            if ($slug == '') {
                return null;
            }

            $ppath = str_replace('\\', '/' , dirname($path));

            // Find or create parent(s).
            $parent = $this->getPage($ppath != '/' ? $ppath : '');

            // Create page.
            $page = new Page;
            $page->parent($parent);
            $page->filePath($parent->path() . '/' . $slug . '/' . $page->name());

            // Add routing information.
            $pages->addPage($page, $path);

            // Set if Modular
            $page->modularTwig($slug[0] == '_');

            // Determine page type.
            if (isset($this->session->{$page->route()})) {
                // Found the type and header from the session.
                $data = $this->session->{$page->route()};

                $header = ['title' => $data['title']];

                if (isset($data['visible'])) {
                    if ($data['visible'] == '' || $data['visible']) {
                        // if auto (ie '')
                        $children = $page->parent()->children();
                        foreach ($children as $child) {
                            if ($child->order()) {
                                // set page order
                                $page->order(1000);
                                break;
                            }
                        }
                    }

                }

                if ($data['name'] == 'modular') {
                    $header['body_classes'] = 'modular';
                }

                $name = $page->modular() ? str_replace('modular/', '', $data['name']) : $data['name'];
                $page->name($name . '.md');
                $page->header($header);
                $page->frontmatter(Yaml::dump((array)$page->header(), 10, 2, false));
            } else {
                // Find out the type by looking at the parent.
                $type = $parent->childType() ? $parent->childType() : $parent->blueprints()->get('child_type',
                    'default');
                $page->name($type . CONTENT_EXT);
                $page->header();
            }
            $page->modularTwig($slug[0] == '_');
        }

        return $page;
    }

    /**
     * Prepare a page to be stored: update its folder, name, template, header and content
     *
     * @param \Grav\Common\Page\Page $page
     * @param object                 $post
     */
    private function preparePage(\Grav\Common\Page\Page $page, $post = null)
    {
        $post = (array)$post;

        if (isset($post['order'])) {
            $order = max(0, (int) isset($post['order']) ? $post['order'] : $page->value('order'));
            $ordering = $order ? sprintf('%02d.', $order) : '';
            $slug = empty($post['folder']) ? $page->value('folder') : (string) $post['folder'];
            $page->folder($ordering . $slug);
        }

        if (isset($post['name']) && !empty($post['name'])) {
            $type = (string) strtolower($post['name']);
            $name = preg_replace('|.*/|', '', $type);
            $page->name($name);
            $page->template($type);
        }

        if (isset($post['header'])) {
            $header = $post['header'];
            $page->header((object) $header);
            $page->frontmatter(Yaml::dump((array) $page->header()));
        }

        $language = trim(basename($page->extension(), 'md'), '.') ?: null;
        $filename = str_replace($language, $post['lang'], $page->name());
        $path = $page->path() . DS . $filename;
        $page->filePath($path);

        if (isset($post['content'])) {
            $page->rawMarkdown((string) $post['content']);
            $page->content((string) $post['content']);
            $page->file()->markdown($page->rawMarkdown());
        }


        return $page;
    }
}
