--
-- Trigger updating user score when new vote on question
--
DROP TRIGGER IF EXISTS UpdateScoreQ;
CREATE TRIGGER UpdateScoreQ AFTER INSERT ON Vote
WHEN new.type = "question"
BEGIN
    UPDATE User
        SET score = score + 1
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score when new vote on comment
--
DROP TRIGGER IF EXISTS UpdateScoreC;
CREATE TRIGGER UpdateScoreC AFTER INSERT ON Vote
WHEN new.type = "comment"
BEGIN
    UPDATE User
        SET score = score + 1
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score when new vote on answer
--
DROP TRIGGER IF EXISTS UpdateScoreA;
CREATE TRIGGER UpdateScoreA AFTER INSERT ON Vote
WHEN new.type = "answer"
BEGIN
    UPDATE User
        SET score = score + 1
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score when adding question
--
DROP TRIGGER IF EXISTS UpdateScoreNewQ;
CREATE TRIGGER UpdateScoreNewQ AFTER INSERT ON Question
BEGIN
    UPDATE User
        SET score = score + 1
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score when adding answer
--
DROP TRIGGER IF EXISTS UpdateScoreNewA;
CREATE TRIGGER UpdateScoreNewA AFTER INSERT ON Answer
BEGIN
    UPDATE User
        SET score = score + 1
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score when adding comment
--
DROP TRIGGER IF EXISTS UpdateScoreNewC;
CREATE TRIGGER UpdateScoreNewC AFTER INSERT ON Comment
BEGIN
    UPDATE User
        SET score = score + 1
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score when answer is accepted
--
DROP TRIGGER IF EXISTS UpdateScoreAcceptedQ;
CREATE TRIGGER UpdateScoreAcceptedQ AFTER UPDATE OF "accepted" ON Answer
WHEN old.accepted = 0 AND new.accepted = 1
BEGIN
    UPDATE User
        SET score = score + 10
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score when accepted status is removed
--
DROP TRIGGER IF EXISTS UpdateScoreAcceptedQ;
CREATE TRIGGER UpdateScoreAcceptedQ AFTER UPDATE OF "accepted" ON Answer
WHEN old.accepted = 1 AND new.accepted = 0
BEGIN
    UPDATE User
        SET score = score - 10
        WHERE id = new.user_id;
END;



--
-- Trigger updating user score on upvote
--
DROP TRIGGER IF EXISTS UpdateScoreOnUpvote;
CREATE TRIGGER UpdateScoreOnUpvote AFTER INSERT ON Vote
WHEN new.vote = 1
BEGIN
    UPDATE User
        SET score = score + 3
        WHERE id IN (SELECT user_id FROM Question WHERE id = new.post_id);
END;



--
-- Trigger updating user score on downvote
--
DROP TRIGGER IF EXISTS UpdateScoreOnDownvote;
CREATE TRIGGER UpdateScoreOnDownvote AFTER INSERT ON Vote
WHEN new.vote = -1
BEGIN
    UPDATE User
        SET score = score - 3
        WHERE id IN (SELECT user_id FROM Question WHERE id = new.post_id);
END;



--
-- Trigger updating user score on upvote
--
DROP TRIGGER IF EXISTS UpdateScoreOnUpvoteU;
CREATE TRIGGER UpdateScoreOnUpvoteU AFTER UPDATE OF "vote" ON Vote
WHEN new.vote = 1 AND old.vote = -1
BEGIN
    UPDATE User
        SET score = score + 3
        WHERE id IN (SELECT user_id FROM Question WHERE id = new.post_id);
END;



--
-- Trigger updating user score on downvote
--
DROP TRIGGER IF EXISTS UpdateScoreOnDownvoteU;
CREATE TRIGGER UpdateScoreOnDownvoteU AFTER UPDATE OF "vote" ON Vote
WHEN new.vote = -1 AND old.vote = 1
BEGIN
    UPDATE User
        SET score = score - 3
        WHERE id IN (SELECT user_id FROM Question WHERE id = new.post_id);
END;
