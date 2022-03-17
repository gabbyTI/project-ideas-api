# The Project Idea API

A Laravel based API that exposes endpoints for retrieving data from the project-ideas application.

  - [Dependencies](#dependencies)
  - [Initial Setup](#initial-setup)
  - [Running Locally](#running-locally)
  - [Running database migrations](#running-database-migrations)
  - [API Documentation](#api-documentation)

## Dependencies

Before you get started, you need to install 

[Docker Desktop](https://www.docker.com/products/docker-desktop).

[Composer](https://getcomposer.org/download/).

## Initial Setup

Clone the project from github by running the following command
```
git clone https://github.com/gabbyTI/project-ideas-api.git
```

## Running Locally

From inside the `project-ideas-api` folder run the following command.

```
composer run project-setup-development
```

Now, to run the project in a docker environment, run the following command.

```
docker-compose up -d
```

The first time you run the above command it takes a few minutes, but subsequent runs are quick.

Once the application's Docker containers have been started, you can access the application in your web browser at: [http://localhost:7077](http://localhost:7077).

## Running database migrations

Before running the migrations, make sure you have started the docker container by running the command in the previous step.

To run the migrations, run the following command.

```
docker exec project_idea-php php artisan migrate
```

## API Documentation

View the API documentation at [http://localhost:8888/api/documentation](http://localhost:7077/api/documentation).

