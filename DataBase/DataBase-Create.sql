CREATE DATABASE movie_pass;

USE movie_pass;

CREATE TABLE `provincia` (
	`id` SMALLINT(2) NOT NULL,
	`provincia_nombre` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `ciudad` (
	`id` INT(4) NOT NULL,
	`ciudad_nombre` VARCHAR(60) NOT NULL,
	`cp` INT(4) NOT NULL,
	`provincia_id` SMALLINT(2) NOT NULL,
	PRIMARY KEY (`id`), KEY `cp` (`cp`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE movies(
	id_movie INT NOT NULL,
	title VARCHAR(50),
	length INT,
	synopsis TEXT,
	poster_url VARCHAR(50),
	video_url VARCHAR(40),
	release_date DATE,
	CONSTRAINT PRIMARY KEY(id_movie));

CREATE TABLE genres(
	id_genre INT NOT NULL,
	genre_name VARCHAR(30),
	CONSTRAINT PRIMARY KEY (id_genre));

CREATE TABLE genres_x_movies(
	id_gxm INT NOT NULL AUTO_INCREMENT,
	id_genre INT NOT NULL,
	id_movie INT NOT NULL,
	CONSTRAINT PRIMARY KEY(id_gxm),
	CONSTRAINT fk_genre FOREIGN KEY(id_genre) REFERENCES genres(id_genre),    
	CONSTRAINT fk_movie FOREIGN KEY(id_movie) REFERENCES movies(id_movie));

CREATE TABLE users(
	id_user INT NOT NULL,
	email VARCHAR(50) UNIQUE,
	pass VARCHAR(12),
	first_name VARCHAR(20),
	last_name VARCHAR(20),
	dni INT NOT NULL,
	user_type INT NOT NULL,
	role_description VARCHAR(50),
	CONSTRAINT PRIMARY KEY (id_user));

CREATE TABLE cinemas(
	id_cinema INT NOT NULL,
	name_cinema VARCHAR(40),
	id_province SMALLINT,
	id_city INT,
	id_user INT,
	address VARCHAR(40),
	CONSTRAINT pk_cinemas PRIMARY KEY(id_cinema),
	CONSTRAINT pk_cinemas_province FOREIGN KEY(id_province) REFERENCES provincia(id),
	CONSTRAINT pk_cinemas_city FOREIGN KEY(id_city) REFERENCES ciudad(id),
	CONSTRAINT fk_id_user foreign key(id_user) references users(id_user) on delete cascade on update cascade);

CREATE TABLE rooms(
	id_room INT NOT NULL,
	id_cinema INT NOT NULL,
	descript VARCHAR(80),
	capacity INT NOT NULL,
	ticket_price FLOAT NOT NULL,
	CONSTRAINT PRIMARY KEY (id_room),
	CONSTRAINT fk_id_cinema FOREIGN KEY (id_cinema) REFERENCES cinemas(id_cinema)ON DELETE CASCADE ON UPDATE CASCADE);

CREATE TABLE projections(
	id_proj INT NOT NULL,
	id_room INT NOT NULL,
	id_movie INT NOT NULL,
	proj_date DATE,
	proj_time TIME,
	CONSTRAINT PRIMARY KEY (id_proj),
	CONSTRAINT fk_id_room FOREIGN KEY (id_room) REFERENCES rooms(id_room) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_id_movie FOREIGN KEY (id_movie) REFERENCES movies(id_movie));
    
CREATE TABLE purchases(
	id_purchase INT AUTO_INCREMENT,
	id_user INT NOT NULL,
	quantity_tickets INT NOT NULL,
	discount FLOAT,
	purchase_date DATE NOT NULL,
	total FLOAT NOT NULL,
	CONSTRAINT pk_id_purchase PRIMARY KEY(id_purchase),
	CONSTRAINT fk_id_user_pur FOREIGN KEY (id_user) REFERENCES users (id_user));

CREATE TABLE tickets(
	id_ticket INT AUTO_INCREMENT,
	id_proj INT NOT NULL,
	id_purchase INT NOT NULL,
	nro_ticket INT NOT NULL,
	CONSTRAINT pk_ticket PRIMARY KEY(id_ticket),
	CONSTRAINT fk_id_funcion FOREIGN KEY (id_proj) REFERENCES projections (id_proj),
	CONSTRAINT fk_id_purchase FOREIGN KEY (id_purchase) REFERENCES purchases (id_purchase));
	
CREATE TABLE creditAccounts(
	id_creditAccount INT AUTO_INCREMENT,
	company VARCHAR(20) UNIQUE,
	CONSTRAINT pk_creditAccount PRIMARY KEY(id_creditAccount));
	
CREATE TABLE paymentCC(
	id_paymentCC INT AUTO_INCREMENT,
	id_creditAccount INT NOT NULL,
	id_purchase INT NOT NULL,
	aut_cod INT NOT NULL,
	paymentCC_date DATE,
	total FLOAT NOT NULL,
	CONSTRAINT pk_paymentCC PRIMARY KEY(id_paymentCC),
	CONSTRAINT fk_id_creditAccount FOREIGN KEY (id_creditAccount) REFERENCES creditAccounts(id_creditAccount),
	CONSTRAINT fk_id_purchasePay FOREIGN KEY (id_purchase) REFERENCES purchases(id_purchase));
    
	
CREATE TABLE discounts(
	id_discount INT AUTO_INCREMENT,
	id_creditAccount INT NOT NULL,
	dis_date DATE,
	dis_perc INT NOT NULL,
	CONSTRAINT pk_discount PRIMARY KEY(id_discount),
	CONSTRAINT fk_id_creditAccountDisc FOREIGN KEY (id_creditAccount) REFERENCES creditAccounts(id_creditAccount));

delimiter $$
create procedure select_or_insert(disDate date,creditAccount int,percent int)
begin
IF EXISTS (select * from discounts where discounts.dis_date=disDate and discounts.id_creditAccount=creditAccount) then
                    UPDATE discounts set discounts.dis_perc=percent where discounts.dis_date=disDate and discounts.id_creditAccount=creditAccount;
                    ELSE
                    INSERT INTO discounts (dis_perc,dis_date,id_creditAccount) VALUES (percent,disDate,creditAccount);
                    END IF;
                    end $$
delimiter ;

INSERT INTO genres(id_genre,genre_name) VALUES (28,"Action"),(12,"Adventure"),(16,"Animation"),(35,"Comedy"),(80,"Crime"),(99,"Documentary"),(18,"Drama"),(10751,"Family"),
						(14,"Fantasy"),(36,"History"),(27,"Horror"),(10402,"Music"),(9648,"Mystery"),(10749,"Romance"),(878,"Science Fiction"),
						(10770,"TV Movie"),(53,"Thriller"),(10752,"War"),(37,"Western");

INSERT INTO users (id_user,email,pass,first_name,last_name,dni,user_type,role_description) VALUES (1,"nahuelflores@gmail.com","1999","Nahuel","Flores","123456",3,"Admin");
		
INSERT INTO users (id_user,email,pass,first_name,last_name,dni,user_type,role_description) VALUES (4,"owner2@gmail.com","1999","cosme","fulanito","123456",2,"Owner");

INSERT INTO creditAccounts(company) VALUES ("Visa"),("Mastercard"),("American Express");
INSERT INTO discounts(id_creditAccount,dis_date,dis_perc) VALUES (1,NOW(),5),(3,NOW(),10);
