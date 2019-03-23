# Domain Event

A library for using domain events.

## Installation

`$ composer require krixon/domain-event`

## Development

### Build Images and Run Containers

Build images:
`$ docker-compose build --build-arg builduser=$(id -u) library`

Install dependencies:

`$ docker-compose run --rm library composer install`

### Testing

`$ docker-compose run --rm library composer test`