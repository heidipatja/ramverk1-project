--
-- Creating a User table.
--



--
-- Table User
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "email" VARCHAR(100) UNIQUE NOT NULL,
    "username" TEXT UNIQUE NOT NULL,
    "password" TEXT,
    "presentation" VARCHAR(300),
    "score" INTEGER NOT NULL DEFAULT 0
);
