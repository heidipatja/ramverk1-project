# Project for course ramverk1

## Install

This is a project done for the course ramverk1 at Blekinge Tekniska Högskola.

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
Exit sqlite by using ctrl + D
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
