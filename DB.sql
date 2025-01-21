CREATE DATABASE e_learning;

USE e_learning;

CREATE TABLE users(
	id_user INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(30) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('etudiant','enseignant','admin') NOT NULL,
    status ENUM('non_valide','valide', 'active', 'suspense') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categories(
	id_category INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE cours(
	id_cour INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(150) NOT NULL, 
    description TEXT,
    contenu_type ENUM('video','document') NOT NULL,
    contenu_path VARCHAR(150) NOT NULL,
    id_category INT,
    id_enseignant INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_category) REFERENCES categories(id_category) ON DELETE CASCADE,
    FOREIGN KEY (id_enseignant) REFERENCES users(id_user) ON DELETE CASCADE
);

CREATE TABLE tags(
	id_tag INT PRIMARY KEY AUTO_INCREMENT,
    tag_name VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE cours_tags(
    id_cour INT,
	id_tag INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id_cour,id_tag),
    FOREIGN KEY (id_cour) REFERENCES cours(id_cour) ON DELETE CASCADE,
    FOREIGN KEY (id_tag) REFERENCES tags(id_tag) ON DELETE CASCADE
);

CREATE TABLE inscriptions(
    id_cour INT,
	id_etudiant INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id_cour,id_etudiant),
    FOREIGN KEY (id_cour) REFERENCES cours(id_cour) ON DELETE CASCADE,
    FOREIGN KEY (id_etudiant) REFERENCES users(id_user) ON DELETE CASCADE
);