create table users
(
	id int auto_increment
		primary key,
	email varchar(55) null,
	username varchar(55) null,
	password varchar(64) null,
	description text null,
	created datetime default CURRENT_TIMESTAMP not null
);

