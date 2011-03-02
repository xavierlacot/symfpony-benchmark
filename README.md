# Symfpony benchmark

"Benchmarking Ponies in the wild"

## Introduction

This repository contains a benchmark suite for two similar pony REST APIs, developed with [symfony 1](http://www.symfony-project.org/) and [Symfony2](http://www.symfony-reloaded.org/). This test suite is based on [a work by Fabien Potencier](https://github.com/fabpot/framework-benchs).

For more informations, please see [http://www.symfpony-project.org/](http://www.symfony-project.org/).

## Raw results

    framework                |      rel |      avg |        1 |        2 |        3
    ------------------------ | -------- | -------- | -------- | -------- | --------
    Symfony2Cache            |   9.6251 |   373.87 |   369.49 |   373.75 |   378.36
    symfony1Cache            |   2.2480 |    87.32 |    86.25 |    87.78 |    87.94
    Symfony2ProdJson         |   1.7970 |    69.80 |    69.64 |    70.71 |    69.04
    Symfony2ProdXml          |   1.6106 |    62.56 |    60.92 |    62.73 |    64.03
    symfony1Prod             |   1.2769 |    49.60 |    48.41 |    50.60 |    49.80
    Symfony2Dev              |   1.1467 |    44.54 |    43.43 |    44.09 |    46.09
    symfony1Dev              |   1.0000 |    38.84 |    37.85 |    40.90 |    37.78

## Running the tests

 * First check the apache-restart command in the siege.php file
 * Then run the command :

      php siege.php

This will launch the test suite and print the results on the screen.