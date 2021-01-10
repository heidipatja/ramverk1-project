--
-- Table User
--

INSERT INTO User ("email", "username", "password", "presentation", "score") VALUES
("hepa@hepa.se", "hepa", "$2y$10$hPLJ8Su/OHHaWgpmmSSViuCM37RKvXjWFcRzt1ukAkAiREJVsbZGS", "Hej jag heter Heidi", 11),
("user@user.se", "user", "$2y$10$hPLJ8Su/OHHaWgpmmSSViuCM37RKvXjWFcRzt1ukAkAiREJVsbZGS", "Hej jag är en användare", 7),
("hapa@hapa.se", "hapa", "$2y$10$hPLJ8Su/OHHaWgpmmSSViuCM37RKvXjWFcRzt1ukAkAiREJVsbZGS", "Hej jag heter Heidi", 11);



--
-- Table Question
--
INSERT INTO Question ("title", "content", "user_id") VALUES
("Pingvin", "En fråga om pingviner.", 1),
("Penglings", "Är det samma som pingviner?", 2),
("Panguins", "Lite text.", 1),
("Pongwuings", "Var kan man hitta pongwuings?", 2);



--
-- Table Tag
--
INSERT INTO Tag ("id", "tag") VALUES
(1, "pingviner"),
(2, "penglings"),
(3, "pangwings"),
(4, "namn"),
(5, "antarktis"),
(6, "pongwuings");



--
-- Table TagToQuestion
--
INSERT INTO TagToQuestion ("id", "tag_id", "question_id") VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 6, 4),
(5, 1, 4);



--
-- Table Answer
--
INSERT INTO Answer ("id", "content", "user_id", "question_id") VALUES
(1, "Det här är ett svar", 1, 1),
(2, "Det här är också ett svar", 2, 1),
(3, "Det här är ett svar", 1, 2),
(4, "Svarar på frågan", 1, 4),
(5, "Här kommer ett superduperbra svar!", 2, 1);



--
-- Table Comment
--
INSERT INTO Comment ("id", "content", "user_id", "post_id", "type") VALUES
(1, "Det här är en kommentar.", 1, 1, "question"),
(2, "En kommentar.", 2, 1, "question"),
(3, "Kommentera mera!", 1, 1, "answer"),
(4, "Kommenterar på.", 1, 2, "answer"),
(5, "Förtydligande, menar pangwings", 2, 4, "question")
;


--
-- Table Comment
--
INSERT INTO Vote ("id", "user_id", "post_id", "type", "vote") VALUES
(1, 1, 1, "answer", 1),
(2, 2, 1, "answer", 1);
