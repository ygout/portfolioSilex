drop table if exists t_categorie;
drop table if exists t_projet;
drop table if exists t_user;


create table t_categorie (
    cat_id integer not null primary key auto_increment,
    cat_libelle varchar(20) not null
   
) engine=innodb character set utf8 collate utf8_unicode_ci;

create table t_imageT (
    imageT_id integer not null primary key auto_increment,
    imageT_titre varchar(20) not null
   
) engine=innodb character set utf8 collate utf8_unicode_ci;

create table t_projet (
    projet_id integer not null primary key auto_increment,
    projet_titre varchar(50) not null,
    cat_id integer not null,
    projet_image varchar(50) not null,
    projet_description varchar(1000) not null,
    projet_bilan varchar(1000) not null,
    projet_outils varchar(500) not null,
    projet_langages varchar(500) not null,
    constraint fk_projet_cat foreign key(cat_id) references t_categorie(cat_id)
    
) engine=innodb character set utf8 collate utf8_unicode_ci;

create table t_user (
    usr_id integer not null primary key auto_increment,
    usr_name varchar(50) not null,
    usr_password varchar(88) not null,
    usr_salt varchar(23) not null,
    usr_role varchar (50) not null
   
) engine=innodb character set utf8 collate utf8_unicode_ci;