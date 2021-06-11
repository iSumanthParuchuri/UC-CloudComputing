CREATE TABLE UserDetails( id int(10) NOT NULL AUTO_INCREMENT, username varchar(60) NOT NULL, email varchar(60) NOT NULL, firstname varchar(30) NOT NULL, lastname varchar(30) NOT NULL, createddatetime DATETIME DEFAULT                                                                                         CURRENT_TIMESTAMP, PRIMARY KEY(id));

ALTER TABLE UserDetails ADD COLUMN filename varchar(30)
