CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_title VARCHAR(1000) NOT NULL,
    article_author VARCHAR(255) NOT NULL DEFAULT 'Hayden Bradfield',
    published_date DATE NOT NULL,
    updated_date DATE,
    content TEXT,
    is_visible BOOLEAN DEFAULT 1
);