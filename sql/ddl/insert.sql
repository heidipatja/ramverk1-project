--
-- Table User
--

INSERT INTO User ("email", "username", "password", "presentation", "score") VALUES
("hepa@hepa.se", "hepa", "$2y$10$hPLJ8Su/OHHaWgpmmSSViuCM37RKvXjWFcRzt1ukAkAiREJVsbZGS", "Hej jag heter Heidi", 11),
("user@user.se", "user", "$2y$10$hPLJ8Su/OHHaWgpmmSSViuCM37RKvXjWFcRzt1ukAkAiREJVsbZGS", "Hej jag är en användare", 7);



--
-- Table Question
--
INSERT INTO Question ("title", "content", "user_id") VALUES
("Pingvin", "En fråga om pingviner.", 1),
("Penglings", "Är det samma som pingviner?", 2),
("Panguins", "Lite text.", 1);



--
-- Table Tag
--
INSERT INTO Tag ("id", "tag") VALUES
(1, "pingviner"),
(2, "penglings"),
(3, "pangwings"),
(4, "namn"),
(5, "antarktis");



--
-- Table TagToQuestion
--
INSERT INTO TagToQuestion ("id", "tag_id", "question_id") VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2);



--
-- Table Answer
--
INSERT INTO Answer ("id", "content", "user_id", "question_id") VALUES
(1, "Det här är ett svar", 1, 1),
(2, "Det här är också ett svar", 2, 1),
(3, "Det här är ett svar", 1, 2);



--
-- Table Comment
--
INSERT INTO Comment ("id", "content", "user_id", "post_id", "type") VALUES
(1, "Det här är en kommentar.", 1, 1, "question"),
(2, "En kommentar.", 2, 1, "question"),
(3, "Kommentera mera!", 1, 1, "answer"),
(4, "Kommenterar på.", 1, 2, "answer");
