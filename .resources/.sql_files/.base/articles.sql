CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_title VARCHAR(1000) NOT NULL,
    article_author VARCHAR(255) NOT NULL DEFAULT 'Hayden Bradfield',
    published_date DATE NOT NULL,
    updated_date DATE,
    content TEXT,
    tile_image_uri TEXT,
    tile_image_alt VARCHAR(255),
    is_featured BOOLEAN DEFAULT 0,
    is_visible BOOLEAN DEFAULT 1,
    slug VARCHAR(1000) NOT NULL
);