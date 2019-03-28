Domain Event
=====

[![Build Status](https://travis-ci.org/krixon/domain-event.svg?branch=master)](https://travis-ci.org/krixon/domain-event)
[![Coverage Status](https://coveralls.io/repos/github/krixon/domain-event/badge.svg?branch=master)](https://coveralls.io/github/krixon/domain-event?branch=master)
[![Latest Stable Version](https://poser.pugx.org/krixon/domain-event/v/stable)](https://packagist.org/packages/krixon/domain-event)
[![Latest Unstable Version](https://poser.pugx.org/krixon/domain-event/v/unstable)](https://packagist.org/packages/krixon/domain-event)

A library for using domain events.

# Installation

`$ composer require krixon/domain-event`

# Development

## Build Images and Run Containers

Build images:

`$ docker-compose build --build-arg builduser=$(id -u) library`

Install dependencies:

`$ docker-compose run --rm library composer install`

## Coding Standard

This library uses a customised version of the Doctrine coding standard that must be followed at all times. If you're 
using PHPStorm you can make this easier by enabling the Code Sniffer inspection:

Navigate to `Settings > Editor > Inspections` and select `PHP > Quality Tools > PHP Code Sniffer validation` from the list.
Enable the inspection.
Set the severity to `ERROR` and check the show warning as checkbox with the option `WEAK WARNING`.
Select the coding standard Custom and point the rule set path to the `phpcs.xml.dist` file at the root of the project.

To check conformance use:

`$ docker-compose run --rm library composer cs`

## Testing

`$ docker-compose run --rm library composer test`

# Change log

All notable changes are recorded in [CHANGELOG](CHANGELOG.md).