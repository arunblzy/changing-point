# Game of Life

A PHP implementation of Conway's Game of Life developed as an interview assignment.

## Requirements

* PHP 8.1 or later
* Composer

## Installation

Clone the repository and navigate to the project root directory:

```bash
git clone https://github.com/arunblzy/changing-point.git
cd changing-point
```

Install the project dependencies:

```bash
composer install
```

## Running the Application

Start the PHP development server from the project root:

```bash
php -S localhost:8000
```

The application will be available at:

```
http://localhost:8000
```

## Configuration

You can configure the number of generations in:

```
config/config.php
```

By default, the project is configured to generate **10 generations**:

```php
'generations' => 10,
```

Update this value as needed to simulate more or fewer generations.
