<?php
namespace Grav\Plugin\Api;

use Grav\Common\Grav;

class Resource
{
    /**
     * Constructor.
     */
    public function __construct(Grav $grav)
    {
        $this->grav = $grav;
    }

    /**
     * Execute the request
     *
     * @return string the output to render
     */
    public function execute()
    {
        $httpMethod = $this->getMethod();

        if ($httpMethod == 'get') {
            if ($this->getIdentifier()) {
                $return = $this->getItem();
            } else {
                $return = $this->getList();
            }
        } else {
            $method = $httpMethod . 'Item';
            $return = $this->$method();
        }

        if ($return !== '') {
            return json_encode($return);
        } else {
            return '';
        }

    }

    /**
     * Get the request method
     *
     * @return string the method name, lowercase
     */
    protected function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get the resource name
     *
     * @return string the resource name
     */
    protected function getResource()
    {
        $paths = $this->grav['uri']->paths();
        $paths = array_splice($paths, 1);
        $resource = $paths[0];
        return $resource;
    }

    /**
     * Get the identifier name
     *
     * @return string the resource identifier name
     */
    protected function getIdentifier()
    {
        $paths = $this->grav['uri']->paths();
        $paths = array_splice($paths, 2);

        $identifier = join('/', $paths);
        return $identifier;
    }

    /**
     * Set the response headers based on the content retrieved
     *
     * Use the following response headers:
     * - ETag: An arbitrary string for the version of a representation. Make sure to include the media type in the hash value, because that makes a different representation. (ex: ETag: "686897696a7c876b7e")
     * - Date: Date and time the response was returned (in RFC1123 format). (ex: Date: Sun, 06 Nov 1994 08:49:37 GMT)
     * - Cache-Control: The maximum number of seconds (max age) a response can be cached. However, if caching is not supported for the response, then no-cache is the value. (ex: Cache-Control: 360 or Cache-Control: no-cache)
     * - Expires: If max age is given, contains the timestamp (in RFC1123 format) for when the response expires, which is the value of Date (e.g. now) plus max age. If caching is not supported for the response, this header is not present. (ex: Expires: Sun, 06 Nov 1994 08:49:37 GMT)
     * - Last-Modified: The timestamp that the resource itself was modified last (in RFC1123 format). (ex: Last-Modified: Sun, 06 Nov 1994 08:49:37 GMT)
     *
     * @todo implement
     */
    public function setHeaders()
    {
        header('Content-type: application/json');

        // Calculate Expires Headers if set to > 0
        $expires = $this->grav['config']->get('system.pages.expires');
        if ($expires > 0) {
            $expires_date = gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT';
            header('Cache-Control: max-age=' . $expires);
            header('Expires: '. $expires_date);
        }

        // TODO: Set Last-Modified
        // TODO: Set the ETag
        // TODO: Set the HTTP response code
        // TODO: Set the Date
    }

    /**
     * Set the correct error code
     *
     * @todo implement all error codes
     */
    protected function setErrorCode($code) {
        switch((int)$code) {
            case 401:
                header('HTTP/1.1 401 Unauthorized');
                break;
            case 403:
                header('HTTP/1.1 403 Forbidden');
                break;
            case 404:
                header('HTTP/1.1 404 Not Found');
                break;
            case 405:
                header('HTTP/1.1 405 Not Allowed');
                break;
            case 501:
                header('HTTP/1.1 501 Not Implemented');
                break;
            default:
                header('HTTP/1.1 ' . $code);

        }
    }

    /**
     * Get list action
     *
     * @return
     */
    public function getList()
    {
        $this->setErrorCode(501); //Not Implemented
        return;
    }

    /**
     * Get item action
     *
     * @return
     */
    public function getItem()
    {
        $this->setErrorCode(501); //Not Implemented
        return;
    }

    /**
     * Post action
     *
     * @return
     */
    public function postItem()
    {
        $this->setErrorCode(501); //Not Implemented
        return;
    }

    /**
     * Put action
     *
     * @return
     */
    public function putItem()
    {
        $this->setErrorCode(501); //Not Implemented
        return;
    }

    /**
     * Delete action
     *
     * @return
     */
    public function deleteItem()
    {
        $this->setErrorCode(501); //Not Implemented
        return;
    }

    /**
     * Prepare and return POST data.
     *
     * @param array $post
     * @return array
     */
    protected function getPost()
    {
        return json_decode(file_get_contents('php://input'));
    }


    /**
     * Builds the return message.
     *
     * Example usage from a resource action:
     *
     *   ```
     *   $this->setErrorCode(403);
     *   $message = $this->buildReturnMessage('Page already exists. Cannot create a page with the same route');
     *   return $message;
     *   ```
     *
     * @param array $post
     * @return array
     */
    protected function buildReturnMessage($message) {
        return [
            'message' => $message
        ];
    }

}
