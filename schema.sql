-- schema.sql
-- creates the database 'Comp440SocialNetworkWebsite' if it does not exist, then creates the 'Users' table
-- import this file on phpMyAdmin

CREATE DATABASE IF NOT EXISTS Comp440SocialNetworkWebsite
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE Comp440SocialNetworkWebsite; -- switch to the 'Comp440SocialNetworkWebsite' database before creating the table

CREATE TABLE IF NOT EXISTS Users (
  username VARCHAR(50) PRIMARY KEY,
  password VARCHAR(255) NOT NULL,
  firstName VARCHAR(50) NOT NULL,
  lastName VARCHAR(50) NOT NULL,
  email VARCHAR(100) UNIQUE,
  phone VARCHAR(20) UNIQUE,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
