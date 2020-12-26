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
("Hejsan", "Hej hej.", 1),
("Ny fråga", "Vad händer?", 2),
("Wopp", "Lite text.", 1);
