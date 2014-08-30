Fairytale
=========

[![TravisCI Status](https://travis-ci.org/nfqakademija/fairytale.svg?branch=master)](https://travis-ci.org/nfqakademija/fairytale)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nfqakademija/fairytale/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nfqakademija/fairytale/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/53e1446b151b35a8d100009d/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53e1446b151b35a8d100009d)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6551879e-855e-433c-b355-a80b2fd897e5/mini.png)](https://insight.sensiolabs.com/projects/6551879e-855e-433c-b355-a80b2fd897e5)
  
NFQ Library

Startup
=======

Initial setup (dependencies and database structure)
```
$ composer install
$ app/console doctrine:database:create
```

Load data fixtures
```
$ app/console doctrine:schema:drop --force; app/console doctrine:schema:create && app/console doctrine:fixtures:load -n
```
