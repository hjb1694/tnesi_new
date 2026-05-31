CREATE TABLE IF NOT EXISTS knox_fire_poll_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    is_resident BOOLEAN NOT NULL,
    is_of_age BOOLEAN NOT NULL,
    has_already_voted BOOLEAN NOT NULL,
    is_yes_vote BOOLEAN NOT NULL,
    ip VARCHAR(255),
    submitted_at TIMESTAMP NOT NULL DEFAULT NOW()
);