create table users(
    username varchar(30) primary key,
    password varchar(30) not null,
    number_of_pixel_zones integer not null
);

create table logins(
    username varchar(30) primary key,
    token varchar(50) not null,
    expiration integer not null
);

create table pixels(
    username varchar(30) not null,
    id integer not null,
    x integer not null,
    y integer not null,
    w integer not null,
    h integer not null,
    picture integer,
    pic_id integer not null,
    primary key(username, id)
);