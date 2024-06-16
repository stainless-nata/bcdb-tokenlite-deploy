# BigchainDB Integration in Laravel
This project demonstrates how to integrate BigchainDB into a Laravel application using a custom BigchainDB driver. This README will guide you through the setup and usage of the integration.

## Features
- Custom BigchainDB driver for Laravel
- Easy integration with Laravel's Eloquent ORM
- Secure and scalable blockchain database
- Requirements
- PHP 7.x or higher
- Laravel 5.x or higher

## Requirements
BigchainDB Driver (HTTP Server)

## Installation
Step 1: Install Dependencies
Install the required dependencies using Composer:

    composer install

Step 2: Configure Environment Variables

Copy the .env.example file to .env and configure your environment variables:

    cp .env.example .env

Update the .env file with your BigchainDB configuration:

    BIGCHAINDB_DRIVER=http://<hostname>:2466/