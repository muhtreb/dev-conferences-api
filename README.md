# DEVConf.net API

This is the API for the DEVConf.net website. It is a RESTful API that provides access to the data stored in the DEVConf.net database.

## API Documentation

The API documentation is available at [https://api.devconf.net/doc](https://api.devconf.net/doc).

## Database

If you want to get database access, please contact me at [muhtreb@protonmail.com](mailto:muhtreb@protonmail.com).

## Installation

Clone project [https://github.com/muhtreb/dev-conferences-local](https://github.com/muhtreb/dev-conferences-local) and follow the instructions in the README file.

Then run the following commands:
```bash
make up
make composer-install
make fixtures
make index
```

## Access

The API is available at [https://api-devconf.traefik.me/doc](https://api-devconf.traefik.me/doc).