-- EFFACEMENT DES TABLES

DROP TABLE IF EXISTS projet_tech;
DROP TABLE IF EXISTS tech;
DROP TABLE IF EXISTS projet;
DROP TABLE IF EXISTS tech_cat;
DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS user;

-- CREATION TABLE USER

CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL 
) ENGINE=InnoDB;

-- CREATION TABLE IMAGES 

CREATE TABLE image (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    type VARCHAR(55) NOT NULL,
    taille INT,
    bin LONGBLOB
) ENGINE=InnoDB;

-- CREATION TABLE PROJETS

CREATE TABLE projet (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description VARCHAR(1000) NOT NULL,
    lien VARCHAR(255),
    image_id INT,
    FOREIGN KEY (image_id) REFERENCES image(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- CREATION TABLE TECH_CAT

CREATE TABLE tech_cat (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- CREATION TABLE TECH

CREATE TABLE tech (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    image_id INT,
    tech_cat_id INT,
    FOREIGN KEY (image_id) REFERENCES image(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (tech_cat_id) REFERENCES tech_cat(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

-- CREATION TABLE ASSOCIATIVE PROJET_TECH

CREATE TABLE projet_tech (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    projet_id INT,
    tech_id INT, 
    FOREIGN KEY (projet_id) REFERENCES projet(id) ON UPDATE CASCADE,
    FOREIGN KEY (tech_id) REFERENCES tech(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

