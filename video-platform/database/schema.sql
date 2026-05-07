-- schema.sql - Alle tabellen voor de database
-- Voer dit eenmalig uit in phpMyAdmin of via de terminal
-- om alle tabellen aan te maken

-- ============================================
-- StreamHive Database Schema
-- 1 op 1 gebaseerd op ERD + UML diagram
-- ============================================

CREATE TABLE `users` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `email`         VARCHAR(255)    NOT NULL,
    `password`      VARCHAR(255)    NOT NULL,
    `role`          VARCHAR(50)     NOT NULL,
    `bio`           TEXT,
    `profile_image` VARCHAR(255),
    `created_at`    TIMESTAMP       NOT NULL,

    PRIMARY KEY (`id`)
);

CREATE TABLE `videos` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `user_id`       INT             NOT NULL,
    `title`         VARCHAR(255)    NOT NULL,
    `description`   TEXT,
    `filename`      VARCHAR(255)    NOT NULL,
    `thumbnail`     VARCHAR(255),
    `views`         INT             NOT NULL,
    `created_at`    TIMESTAMP       NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

CREATE TABLE `likes` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `user_id`       INT             NOT NULL,
    `video_id`      INT             NOT NULL,
    `created_at`    TIMESTAMP       NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`)  REFERENCES `users`  (`id`),
    FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`)
);

CREATE TABLE `comments` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `user_id`       INT             NOT NULL,
    `video_id`      INT             NOT NULL,
    `content`       TEXT            NOT NULL,
    `created_at`    TIMESTAMP       NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`)  REFERENCES `users`  (`id`),
    FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`)
);

CREATE TABLE `subscriptions` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `subscriber_id` INT             NOT NULL,
    `leader_id`     INT             NOT NULL,
    `created_at`    TIMESTAMP       NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`subscriber_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`leader_id`)     REFERENCES `users` (`id`)
);

CREATE TABLE `password_reset` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `user_id`       INT             NOT NULL,
    `token`         VARCHAR(255)    NOT NULL,
    `expires_at`    TIMESTAMP       NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

CREATE TABLE `categories` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `video_id`      INT             NOT NULL,
    `name`          VARCHAR(100)    NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`)
);