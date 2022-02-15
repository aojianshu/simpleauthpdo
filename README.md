# Simple Auth PDO
Demonstration for Simple Authentication using PDO for Grade 12 IT Students of Iligan Computer Insitute.

## How to Use
Copy this script in your mysql cli.
```
  CREATE DATABASE simpleauthpdo;

  CREATE TABLE users (
  id int primary key auto_increment,
  username varchar(50) not null unique,
  password varchar(255) not null,
  created_at datetime default current_timestamp
  );
```
