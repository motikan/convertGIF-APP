Convert Animation-GIF App
=======================
### Setup in Mac
```
$ phpbrew install 7.1.0 +default +mysql +fpm +openssl +curl +openssl=/usr -- --with-libdir=lib64
$ phpbrew ext install gd -- --with-jpeg-dir=/usr/local/Cellar/
```
```sql
create table image(
    id int primary key auto_increment,
    image_name varchar(255) not null unique,
    ipaddr varchar(20),
    created_at datetime not null default current_timestamp,
    deleted_at datetime default null
);
```