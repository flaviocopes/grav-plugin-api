# Grav API Plugin

The **API Plugin** for [Grav](http://github.com/getgrav/grav) adds a REST API to Grav.

| IMPORTANT!!! This plugin is currently in development as is to be considered a **beta release**.  As such, use this in a production environment **at your own risk!**. More features will be added in the future.

# Installation

The API plugin is easy to install with GPM.

```
$ bin/gpm install api
```

Or clone from GitHub and put in the `user/plugins/api` folder.

# Principles

- Two URLs per resource: one for the resource collection, one for the individual resource
- Use plural forms
- Keep URLs as short as possible
- Use nouns as resource names

HTTP Method Names
- GET: read a resource or collection
- POST: create a resource
- PUT: update a resource
- DELETE: remove a resource

# HTTP Status Codes

Use meaningful HTTP Status Codes (TODO)

- 200: Success.
- 201: Created. Returned on successful creation of a new resource. Include a 'Location' header with a link to the newly-created resource.
- 400: Bad request. Data issues such as invalid JSON, etc.
- 403: Forbidden. Example: trying to create a page already existing
- 404: Not found. Resource not found on GET.
- 405: Not Allowed.
- 501: Not Implemented. Returned by default by non implemented API methods

# Things still missing

- Support filtering, sorting and pagination on collections
- Allow clients to reduce the number of fields that come back in the response
- Access control to each API method, for groups or single users
- Use http://fractal.thephpleague.com
- Allow plugins to add API methods
- Allow to limit access to a specific IP (range)
- Use OAuth2 to secure the API http://oauth2.thephpleague.com/
- JSONP
- Return the children of a page in the page request, if desired
- Allow to only retrieve some fields on API calls that are heavy on the system (e.g. `GET /api/pages/:page`)

# Authentication

In this current form the Grav API uses Basic Authentication, with the username and password currently set in Grav.
It's just a first implementation, willing to change that at least with digest authentication.

Users with `admin.super` or `admin.api` permissions can access the whole API.

## Usage or Basic Authentication on the client-side

On the client side, add the authorization header of the request as follows:

```
Authorization: Basic <Base64 encoded value>
```

the base64 value is username:password

# Standard Grav API Endpoints

## Implemented

### Version

Methods that return the API plugin version. Depending on the returned value, some methods might be available or not depending on the plugin evolution. To be used by clients when interacting with the Grav API.

### GET /api/version

Get the API plugin major version.

e.g. "0" or "1"

### GET /api/version/major

Same as `GET /api/version`

e.g. "0" or "1"

### GET /api/version/minor

Get the API plugin major and minor version.

e.g. "0.1" or "1.0"

### GET /api/version/full

Get the API plugin full version.

e.g. "0.1.3"

### Pages

#### GET /api/pages

Lists the site pages

#### GET /api/pages/:page

Get a specific page

e.g. /api/pages/blog/my-first-post
e.g. /api/pages/contact

### Users

#### GET /api/users

Lists the site users

#### GET /api/users/:user

Get a specific user

e.g. /api/users/admin
e.g. /api/users/andy

## Not yet implemented

- GET /api/plugins
- GET /api/plugins/shoppingcart
- GET /api/themes
- GET /api/themes/theme
- GET /api/data
- GET /api/logs
- GET /api/config
- GET /api/config/system
- GET /api/config/plugin/:pluginName
- GET /api/backups
- GET /api/gpm
- POST /api/gpm/update
- GET /api/grav
- POST /api/grav/self-upgrade
- GET /api/version
- GET /api/debug
- .....
