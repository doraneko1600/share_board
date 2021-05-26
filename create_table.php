<?php

require_once('config.php');
$pdo = new PDO(DSN, DB_USER, DB_PASS);

//userdata
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists userdata(
        id int not null auto_increment,
        email varchar(255) not null,
        name varchar(255) character set utf8 collate utf8_general_ci not null,
        password varchar(255) not null,
        failed_count int not null default 0,
        locked_time datetime,
        verify int not null default 0,
        verify_team int not null default 0,
        created timestamp not null default current_timestamp,
        primary key(id),
        unique key(email)
      )");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

//secret_question
/*
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists secret_question(
        id int not null auto_increment,
        email varchar(255) not null,
        question int not null,
        anser varchar(255) not null,
        created timestamp not null default current_timestamp,
        primary key(id),
        unique key(email),
            foreign key(email)
            references userdata(email)
            on delete cascade on update cascade
      )");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
*/

//teamdata
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists teamdata(
        id int not null auto_increment,
        email varchar(255) not null,
        team varchar(255) character set utf8 collate utf8_general_ci not null,
        password varchar(255) not null,
        created timestamp not null default current_timestamp,
        primary key(id),
        unique key(team),
            foreign key (email)
            references userdata(email)
            on delete cascade on update cascade 
    )");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

//team_members
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists team_members(
        id int not null auto_increment,
        email varchar(255) not null,
        name varchar(255) character set utf8 collate utf8_general_ci not null,
        team varchar(255) character set utf8 collate utf8_general_ci not null,
        created timestamp not null default current_timestamp,
        primary key(id),
        unique key(email, team),
            foreign key (email)
            references userdata(email)
            on delete cascade on update cascade,
            foreign key (team)
            references teamdata(team)
            on delete cascade on update cascade
    )");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

//chat
try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists chat(
        id int not null auto_increment primary key,
        team varchar(255) character set utf8 collate utf8_general_ci not null,
        email varchar(255) not null,
        c_name varchar(255) character set utf8 collate utf8_general_ci not null,
        message varchar(255) character set utf8 collate utf8_general_ci not null,
        created timestamp not null default current_timestamp,
            foreign key (email)
            references userdata(email)
            on delete cascade on update cascade,
            foreign key (team)
            references teamdata(team)
            on delete cascade on update cascade
    )");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

//chat_private
try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists chat_private(
        id int not null auto_increment primary key,
        your_email varchar(255) not null,
        my_email varchar(255) not null,
        message varchar(255) character set utf8 collate utf8_general_ci not null,
        created timestamp not null default current_timestamp,
            foreign key (my_email)
            references userdata(email)
            on delete cascade  on update cascade
    )");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

//plan
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists plan(
        id int not null auto_increment primary key,
        team varchar(255) character set utf8 collate utf8_general_ci not null,
        email varchar(255) not null,
        date varchar(255) not null,
        title varchar(255) character set utf8 collate utf8_general_ci not null,
        details varchar(255) character set utf8 collate utf8_general_ci not null,
        created timestamp not null default current_timestamp,
            foreign key (email)
            references userdata(email)
            on delete cascade  on update cascade,
            foreign key (team)
            references teamdata(team)
            on delete cascade on update cascade
    )");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

//todo
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists todo(
        id int not null auto_increment primary key, 
        email varchar(255) not null,
        team varchar(255) character set utf8 collate utf8_general_ci not null,
        task varchar(255) character set utf8 collate utf8_general_ci not null,
        details varchar(255) character set utf8 collate utf8_general_ci not null,
        period date not null,
        rank int,
        complete int not null default 0,
        created timestamp not null default current_timestamp,
            foreign key (email)
            references userdata(email)
            on delete cascade  on update cascade,
            foreign key (team)
            references teamdata(team)
            on delete cascade on update cascade
    )");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

//images
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("create table if not exists images(
        id int not null auto_increment primary key,
        team varchar(255) character set utf8 collate utf8_general_ci not null,
        email varchar(255) not null,
        image_name varchar(255) character set utf8 collate utf8_general_ci not null,
        image_type varchar(255) not null,
        image_content longblob not null,
        image_size int not null,
        created timestamp not null default current_timestamp,
            foreign key (email)
            references userdata(email)
            on delete cascade  on update cascade,
            foreign key (team)
            references teamdata(team)
            on delete cascade on update cascade
    )");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
