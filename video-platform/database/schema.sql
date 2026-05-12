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
    `id`            INT             NOT NULL-- ============================================
-- StreamHive Database Schema
-- Gebaseerd op ERD + UML diagram
-- ============================================

CREATE TABLE `users` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `email`         VARCHAR(255)    NOT NULL UNIQUE,
    `username`      VARCHAR(50)     NOT NULL UNIQUE,          -- NIEUW: gebruikersnaam
    `password`      VARCHAR(255)    NOT NULL,
    `role`          VARCHAR(50)     NOT NULL DEFAULT 'user',
    `bio`           TEXT,
    `profile_image` VARCHAR(255),
    `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`)
);

CREATE TABLE `videos` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `user_id`       INT             NOT NULL,
    `title`         VARCHAR(255)    NOT NULL,
    `description`   TEXT,
    `filename`      VARCHAR(255)    NOT NULL,
    `thumbnail`     VARCHAR(255),
    `views`         INT             NOT NULL DEFAULT 0,        -- DEFAULT 0 zodat je het niet hoeft mee te geven bij upload
    `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE `likes` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `user_id`       INT             NOT NULL,
    `video_id`      INT             NOT NULL,
    `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_like` (`user_id`, `video_id`),         -- voorkomt dat iemand 2x liked
    FOREIGN KEY (`user_id`)  REFERENCES `users`  (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE
);

CREATE TABLE `comments` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `user_id`       INT             NOT NULL,
    `video_id`      INT             NOT NULL,
    `content`       TEXT            NOT NULL,
    `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`)  REFERENCES `users`  (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE
);

CREATE TABLE `subscriptions` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `subscriber_id` INT             NOT NULL,
    `leader_id`     INT             NOT NULL,
    `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_subscription` (`subscriber_id`, `leader_id`), -- voorkomt dubbele subscriptions
    FOREIGN KEY (`subscriber_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`leader_id`)     REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE `password_reset` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `user_id`       INT             NOT NULL,
    `token`         VARCHAR(255)    NOT NULL UNIQUE,
    `expires_at`    TIMESTAMP       NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE `categories` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `video_id`      INT             NOT NULL,
    `name`          VARCHAR(100)    NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE
); AUTO_INCREMENT,
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