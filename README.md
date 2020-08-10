# Laravel Blog API 

RESTful todo API using [laravel](https://laravel.com/) and [laravel-json-api](https://github.com/cloudcreativity/laravel-json-api) written to better understand the [JSON:API](https://jsonapi.org) specification.   
   
Includes [passport](https://github.com/laravel/passport) authentication and tasks with related tags.




## Table of Contents
  * [Example Response](#example-response)
  * [Installation](#installation)
  * [Making Requests](#making-requests)
    * [1. Request Headers](#request-headers)
    * [2. Endpoint List](#endpoint-list)
  * [Optional Database Seeding](#optional-database-seeding)
  * [Folder Contents](#folder-contents)
  * [Running Tests](#running-tests)


## Example Response

Example response from the `GET /api/v1/tasks/1?include=author` endpoint:  
```json
{
  "data": {
    "type": "tasks",
    "id": "1",
    "attributes": {
      "body": "Impedit rem possimus ea a laborum odio voluptatem ducimus. Quibusdam et alias est facilis rerum sint. Sapiente animi esse et nobis. Cum aut quod nam error.",
      "created-at": "2020-09-28T16:13:34+00:00",
      "updated-at": "2020-09-28T16:13:34+00:00"
    },
    "relationships": {
      "author": {
        "data": {
          "type": "users",
          "id": "1"
        },
        "links": {
          "self": "http:\/\/localhost:8000\/api\/v1\/tasks\/1\/relationships\/author",
          "related": "http:\/\/localhost:8000\/api\/v1\/tasks\/1\/author"
        }
      },
      "editor": {
        "links": {
          "self": "http:\/\/localhost:8000\/api\/v1\/tasks\/1\/relationships\/editor",
          "related": "http:\/\/localhost:8000\/api\/v1\/tasks\/1\/editor"
        }
      },
      "tags": {
        "links": {
          "self": "http:\/\/localhost:8000\/api\/v1\/tasks\/1\/relationships\/tags",
          "related": "http:\/\/localhost:8000\/api\/v1\/tasks\/1\/tags"
        }
      }
    },
    "links": {
      "self": "http:\/\/localhost:8000\/api\/v1\/tasks\/1"
    }
  },
  "included": [
    {
      "type": "users",
      "id": "1",
      "attributes": {
        "display-name": "baz",
        "created-at": "2020-09-28T16:13:34+00:00",
        "updated-at": "2020-09-28T16:13:34+00:00"
      },
      "relationships": {
        "tasks": {
          "links": {
            "self": "http:\/\/localhost:8000\/api\/v1\/users\/1\/relationships\/tasks",
            "related": "http:\/\/localhost:8000\/api\/v1\/users\/1\/tasks"
          }
        },
        "tags": {
          "links": {
            "self": "http:\/\/localhost:8000\/api\/v1\/users\/1\/relationships\/tags",
            "related": "http:\/\/localhost:8000\/api\/v1\/users\/1\/tags"
          }
        }
      }
    }
  ]
}
```



## Installation

Install dependencies with composer:

`composer install`

Copy the base env.example file and modify the .env file to reflect your database config:

`cp .env.example .env`  
`vim .env`

Generate the necessary database schema

`php artisan migrate`

Install passport:

`php artisan passport:install`

Generate app encryption key

`php artisan key:generate`

Generate personal access key

`php artisan passport:client --personal`

Start the local development server

`php artisan serve`



## Making Requests

You can now access the API at http://localhost:8000/api/v1, for example GET http://localhost:8000/api/v1/tasks/1?include=author

#### Request Headers

|     **Required**     | **Key**          | **Value**                |
| -------------------- | ---------------- |------------------------- |
| Yes                  | Accept           | application/vnd.api+json |
| Yes                  | Content-Type     | application/vnd.api+json |
| All but signup/login | Authorization    | Bearer <access_token>    |



#### Endpoint List

**NOTE: When querying tasks with no params, tags are (eagerly) included by default**

| **Method**  | **Endpoint** |         **Params**          |
| ----------- | ------------ | --------------------------- |
| GET         | /auth/logout | -                           |
| GET         | /auth/user   | -                           |
| POST        | /auth/signup | -                           |
| POST        | /auth/login  | -                           |
|             |              |                             |
| GET         | /tasks       | ?include=tags,author,editor |
| GET         | /tasks/1     | ?include=tags,author,editor |
| POST        | /tasks/1     | -                           |
| PATCH       | /tasks/1     | -                           |
| DEL         | /tasks/1     | -                           |
|             |              |                             |
| GET         | /tags        | ?include=tasks              |
| GET         | /tags/1      | ?include=tasks              |
| POST        | /tags/1      | -                           |
| PATCH       | /tags/1      | -                           |
| DEL         | /tags/1      | -                           |



## Optional Database Seeding

Run the following commands to seed a fresh database
 
    php artisan migrate:fresh
    php artisan db:seed



## Folder Contents

- `app` - Eloquent models
- `app/Http/Controllers` - API auth controller
- `app/Http/Middleware` -  Passport auth middleware
- `app/Http/Requests` - API auth form requests
- `app/Http/Resources` - API auth form resources
- `app/JsonApi` - Adapter, Schema and Validators for Tags. Tasks and Users
- `app/Policies` - API Tag and Task policies
- `database/factories` - Model factory for all the models
- `database/migrations` - Database migrations
- `database/seeds` - Database seeders
- `routes/api.php` - API endpoint routes
- `tests/Feature/Http/Controllers` - API tests and dataproviders



## Running Tests

PHPUnit tests can be executed with:

    php artisan test
