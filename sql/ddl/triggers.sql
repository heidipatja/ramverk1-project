--
-- Trigger updating user score when new vote on question
--
CREATE TRIGGER UpdateScore AFTER INSERT ON Vote
WHEN new.type = "question"
BEGIN
    UPDATE User
        SET score = score + 2
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score when new vote on comment
--
CREATE TRIGGER UpdateScore AFTER INSERT ON Vote
WHEN new.type = "comment"
BEGIN
    UPDATE User
        SET score = score + 1
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score when new vote on answer
--
CREATE TRIGGER UpdateScore AFTER INSERT ON Vote
WHEN new.type = "answer"
BEGIN
    UPDATE User
        SET score = score + 3
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score when user's answer is accepted
--
CREATE TRIGGER UpdateScore AFTER UPDATE ON Answer
WHEN new.accepted = 1
BEGIN
    UPDATE User
        SET score = score + 3
        WHERE id = new.user_id;
END;
