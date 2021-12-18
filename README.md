# RTL Challenge

This project is created for [RTL](https://www.rtl.nl/) for testing purpose only.

## Installation

There are 2 different way to test this cli application;

- If you have docker installed this is the recommended way;

```shell
$ docker run --rm yakupars/rtl:latest bin/console populate -s
```

- If you have php binary installed;

```shell
$ git clone https://github.com/yakupars/rtl && cd rtl
$ composer install
$ php bin/console populate filename.csv
```

## Explanation

This cli tool is created by only using Low-Level Date Algorithms. That means you will not find any built-in PHP
functions to manipulate dates. For details about the algorithm, you can
check [date_algorithms](https://howardhinnant.github.io/date_algorithms.html) and you will discover
that `src/Service/EpochDateService.php` is actually a port of internal C code written with PHP.

Project has some additional features implemented like redirecting output to standard output instead of given filepath.
With this approach you would use the application without installing any additional program other than Docker to your
system. (This is just for user convenience. You will not have to map any volumes to the container with this approach.)

### Example

```shell
$ docker run --rm yakupars/rtl:latest bin/console populate -s > export.csv
```

There are no tests written by choice.

One additional feature is the ability of specifying the start date for date population. All options can be examined by
passing help flag to the application as well.

`php bin/console populate --help`

> with **php** by **yakupars** for **fun**
