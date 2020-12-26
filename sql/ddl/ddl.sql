--
-- Table User
--
DROP TABLE IF EXISTS User;
CREATE TABLE User (
    "id" INTEGER NOT NULL,
    "email" TEXT NOT NULL,
    "username" TEXT NOT NULL,
    "password" TEXT,
    "presentation" TEXT,
    "questions" INTEGER NOT NULL DEFAULT 0,
    "answers" INTEGER NOT NULL DEFAULT 0,
    "comments" INTEGER NOT NULL DEFAULT 0,
    "votes" INTEGER NOT NULL DEFAULT 0,
    "score" INTEGER NOT NULL DEFAULT 0,
    "created" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY("id"),
    UNIQUE("email"),
    UNIQUE("username")
);



--
-- Table Question
--
DROP TABLE IF EXISTS Question;
CREATE TABLE Question (
    "id" INTEGER NOT NULL,
    "title" TEXT,
    "content" TEXT,
    "user_id" INTEGER NOT NULL,
    "votes" INTEGER NOT NULL DEFAULT 0,
    "created" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated" DATETIME DEFAULT NULL,
    "deleted" DATETIME DEFAULT NULL,

    PRIMARY KEY("id"),
    FOREIGN KEY("user_id") REFERENCES User("id")
);



--
-- Table Answer
--
DROP TABLE IF EXISTS Answer;
CREATE TABLE Answer (
    "id" INTEGER NOT NULL,
    "content" TEXT,
    "user_id" INTEGER NOT NULL,
    "question_id" INTEGER NOT NULL,
    "accepted" INTEGER NOT NULL DEFAULT 0,
    "votes" INTEGER NOT NULL DEFAULT 0,
    "created" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated" DATETIME DEFAULT NULL,
    "deleted" DATETIME DEFAULT NULL,

    PRIMARY KEY("id"),
    FOREIGN KEY("user_id") REFERENCES User("id"),
    FOREIGN KEY("question_id") REFERENCES Question("id")
);



--
-- Table Tag
--
DROP TABLE IF EXISTS Tag;
CREATE TABLE Tag (
    "id" INTEGER NOT NULL,
    "tag" TEXT NOT NULL,

    PRIMARY KEY("id"),
    UNIQUE("tag")
);



--
-- Table Tag to Question
--
DROP TABLE IF EXISTS TagToQuestion;
CREATE TABLE TagToQuestion (
    "id" INTEGER NOT NULL,
    "tag_id" INTEGER NOT NULL,
    "question_id" INTEGER NOT NULL,

    PRIMARY KEY("id"),
    FOREIGN KEY("tag_id") REFERENCES Tag("id"),
    FOREIGN KEY("question_id") REFERENCES Question("id")
);



--
-- Table Comment
--
DROP TABLE IF EXISTS Comment;
CREATE TABLE Comment (
    "id" INTEGER NOT NULL,
    "content" TEXT NOT NULL,
    "user_id" INTEGER NOT NULL,
    "post_id" INTEGER,
    "type" TEXT,
    "votes" INTEGER NOT NULL DEFAULT 0,
    "created" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated" DATETIME DEFAULT NULL,
    "deleted" DATETIME DEFAULT NULL,

    PRIMARY KEY("id"),
    FOREIGN KEY("user_id") REFERENCES User("id")
);



--
-- Table Vote
--
DROP TABLE IF EXISTS Vote;
CREATE TABLE Vote (
    "id" INTEGER NOT NULL,
    "user_id" INTEGER NOT NULL,
    "post_id" INTEGER,
    "type" TEXT,

    PRIMARY KEY("id"),
    FOREIGN KEY("user_id") REFERENCES User("id")
);
