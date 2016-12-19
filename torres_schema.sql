
CREATE DATABASE torres;
\c torres

DROP TABLE orderdetails;
DROP TABLE orders;
DROP TABLE products;
DROP TABLE users;

CREATE TABLE orderdetails (
             id serial primary key,
             orderid int NOT NULL,
             productname varchar(255) NOT NULL,
             quantityordered int NOT NULL,
             totalamount int NOT NULL);

CREATE TABLE orders (
             id serial primary key,
             userid int NOT NULL,
             totalamount int NOT NULL,
             dateordered timestamp without time zone DEFAULT now());

CREATE TABLE products (
             id serial primary key,
             productname varchar(255) NOT NULL unique,
             description varchar(255),
             status bool DEFAULT true,
             priceeach int NOT NULL,
             instock int DEFAULT 0);

CREATE TABLE users (
             id serial primary key,
             username varchar(255) NOT NULL,
             password varchar(255) NOT NULL,
             isadmin bool DEFAULT false);
