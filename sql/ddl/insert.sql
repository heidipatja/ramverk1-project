--
-- Table User
--

INSERT INTO User ("email", "username", "password", "presentation") VALUES
("hpatja@gmail.com", "hepa", "$2y$10$hPLJ8Su/OHHaWgpmmSSViuCM37RKvXjWFcRzt1ukAkAiREJVsbZGS", "Hej jag heter Heidi"),
("myran@stackoverflod.se", "myran", "$2y$10$hPLJ8Su/OHHaWgpmmSSViuCM37RKvXjWFcRzt1ukAkAiREJVsbZGS", "Jag älskar myror!"),
("stackarn@gmail.com", "stackarn", "$2y$10$hPLJ8Su/OHHaWgpmmSSViuCM37RKvXjWFcRzt1ukAkAiREJVsbZGS", "Stacks are awesome."),
("antti@gmail.com", "antti", "$2y$10$hPLJ8Su/OHHaWgpmmSSViuCM37RKvXjWFcRzt1ukAkAiREJVsbZGS", "What's up jag heter Antti."),
("ants4ever@gmail.com", "ants4ever", "$2y$10$hPLJ8Su/OHHaWgpmmSSViuCM37RKvXjWFcRzt1ukAkAiREJVsbZGS", "Myror är livet.");



--
-- Table Question
--
INSERT INTO Question ("title", "content", "user_id", "created") VALUES
("Hur många ben har myror?", "Jag var ute på promenad och såg en myra som hade fem ben. Brukar det inte vara fler?", 5, "2021-01-12 18:23:10"),
("Myrstackar", "Hur många myrstackar finns i Sverige? Jag har sett jättemånga, men jag har ju inte varit i alla skogar.", 5, "2021-01-03 07:05:59"),
("Lägger myror ägg?", "Ja, lägger de ägg eller får de färdiga barn liksom?", 4, "2021-01-09 08:57:03"),
("Filmtips", "Har ni tips på filmer där det förekommer myror? Tecknat är ok. Skicka gärna länkar.", 2, "2021-01-12 20:33:31"),
("Biten", "Vad gör man om man har blivit biten av en myra? Det svider jättemycket.", 3, "2021-01-10 19:51:34");



--
-- Table Tag
--
INSERT INTO Tag ("id", "tag") VALUES
(1, "myrstackar"),
(2, "filmer"),
(3, "ben"),
(4, "myror"),
(5, "observationer"),
(6, "skogen"),
(7, "bett");



--
-- Table TagToQuestion
--
INSERT INTO TagToQuestion ("id", "tag_id", "question_id") VALUES
(1, 3, 1),
(2, 4, 1),
(3, 5, 1),
(4, 1, 2),
(5, 6, 2),
(6, 4, 3),
(7, 2, 4),
(8, 4, 4),
(9, 7, 5),
(10, 5, 5)
;



--
-- Table Answer
--
INSERT INTO Answer ("id", "content", "user_id", "question_id", "accepted") VALUES
(1, "Har du sett Antz?", 4, 4, 0),
(2, "Jag kan rekommendera [Them!](https://www.imdb.com/title/tt0047573/). En riktig klassiker.", 3, 4, 0),
(3, "De har sex ben. Står på Wikipedia.", 4, 1, 1),
(4, "Det händer nog inte så mycket, men om du är allergisk kan du få en allergisk reaktion.", 5, 5, 0);



--
-- Table Comment
--
INSERT INTO Comment ("id", "content", "user_id", "post_id", "type") VALUES
(1, "Den har jag sett, men tack ändå!", 2, 1, "answer"),
(2, "Tack för tipset!", 2, 2, "answer"),
(3, "Var såg du den?", 1, 1, "question")
;


--
-- Table Comment
--
INSERT INTO Vote ("id", "user_id", "post_id", "type", "vote") VALUES
(1, 1, 4, "question", 1),
(2, 3, 4, "question", 1),
(3, 4, 4, "question", 1),
(4, 1, 1, "question", -1),
(5, 1, 1, "answer", 1),
(6, 2, 1, "answer", 1),
(7, 1, 2, "answer", -1),
(8, 5, 3, "answer", 1)
;
