# Project for course ramverk1

[![CircleCI](https://circleci.com/gh/heidipatja/ramverk1-project.svg?style=svg)](https://circleci.com/gh/heidipatja/ramverk1-project)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/heidipatja/ramverk1-project/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/heidipatja/ramverk1-project/?branch=main)

[![Build Status](https://scrutinizer-ci.com/g/heidipatja/ramverk1-project/badges/build.png?b=main)](https://scrutinizer-ci.com/g/heidipatja/ramverk1-project/build-status/main)

[![Build Status](https://api.travis-ci.com/heidipatja/ramverk1-project.svg?branch=main)](https://travis-ci.com/heidipatja/ramverk1-project)

1. Download project

```
git clone https://github.com/heidipatja/ramverk1-project.git
```


2. Install

```
cd ramverk1-project/
```

```
composer install
```

```
make install
```

```
make install test
```

3. Create database

```
chmod 777 data
```

```
sqlite3 data/db.sqlite
```

```
ctrl + D (to exit database)
```

```
sqlite3 data/db.sqlite < sql/ddl/ddl.sql
sqlite3 data/db.sqlite < sql/ddl/triggers.sql
sqlite3 data/db.sqlite < sql/ddl/insert.sql
```

```
chmod 666 data/db.sqlite
```

```
chmod 777 cache/*
```
