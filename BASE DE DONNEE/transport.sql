CREATE DATABASE transport;
USE transport;
CREATE TABLE users(
	id int AUTO_INCREMENT NOT null PRIMARY KEY,
    prenom varchar(100),
    nom varchar(100),
    email varchar(100) UNIQUE,
    mot_pass varchar(100) NOT null,
    ville_origine varchar(100),
    ville_actuelle varchar(100),
    telephone varchar(100),
    role ENUM("admin","conducteur","client") DEFAULT "client",
    created_at timestamp DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    depart VARCHAR(255) NOT NULL,
    arriver VARCHAR(255) NOT NULL,
    date_time DATETIME NOT NULL,
    passengers INT NOT NULL,
    transport_type VARCHAR(50) NOT NULL,
    additional_info TEXT,
    prix_estime DECIMAL(10, 2) NOT NULL,
    id_users INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_users) REFERENCES users(id)
);

CREATE TABLE commentaire(
	id int AUTO_INCREMENT NOT null PRIMARY KEY,
	nom_conducteur varchar(100),
	conf_conducteur varchar(100),
    commentaires varchar(1000),
    reponse_com varchar(1000),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    id_users int,
    FOREIGN KEY (id_users) REFERENCES users(id),
    created_at timestamp DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE commentaire ADD COLUMN lue_par JSON DEFAULT NULL COMMENT 'Liste des ID utilisateurs ayant vu le commentaire';

CREATE TABLE IF NOT EXISTS reponses_commentaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT NOT NULL,
    user_id INT NOT NULL,
    reponse TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (comment_id) REFERENCES commentaire(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE conducteur(
	id int AUTO_INCREMENT NOT null PRIMARY KEY,
	nom_transport varchar(100),
	type_permi varchar(100),
    information text,
    profil varchar(255),
    id_users int,
    FOREIGN KEY (id_users) REFERENCES users(id),
    created_at timestamp DEFAULT CURRENT_TIMESTAMP
);