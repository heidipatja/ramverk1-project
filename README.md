# Project for course ramverk1

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
