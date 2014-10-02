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
$ app/console assets:install
$ app/console assetic:dump
```

Load data fixtures
```
$ app/console doctrine:schema:drop --force
$ app/console doctrine:schema:create 
$ app/console doctrine:fixtures:load -n --fixtures src/Nfq/Fairytale/CoreBundle/DataFixtures/ORM/
```

Running tests
=============

Test environment (which is being used for tests) has other database configured, thus you need to create it as well.
```
$ app/console doctrine:database:create -e test
```
Run tests.

```
$ bin/behat
```

> HEADS UP: Behat tests are configured to reload fixtures, so running Behat wipes test database!

Regenerating entity classes (99/100 you don't need this)
===========================

After regenerating entity classes from mapping some changes have to be made manually:

## 1. User

https://github.com/FriendsOfSymfony/FOSUserBundle/blob/1.3.x/Resources/doc/index.md#step-3-create-your-user-class

see *Doctrine ORM User class* section:

1. Class must extend `FOS\UserBundle\Entity\User`
2. `id` field must be `protected` (doctrine generates it as `private`)
3. Constructor must call `parent::__construct();`

## 2. Image

1. Image class must implement `Nfq\Fairytale\CoreBundle\Upload\UploadInterface`
2. Constructor must call `$this->createdAt = new \DateTime();`
