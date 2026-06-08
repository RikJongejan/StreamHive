-- schema.sql - Alle tabellen voor de database
-- Voer dit eenmalig uit in phpMyAdmin of via de terminal
-- om alle tabellen aan te maken

-- ============================================
-- StreamHive Database Schema
-- Gebaseerd op ERD + UML diagram en de opdrachteisen
-- ============================================

CREATE TABLE `users` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `email`         VARCHAR(255)    NOT NULL UNIQUE,
    `username`      VARCHAR(50)     NOT NULL UNIQUE,
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

-- Een like staat op OF een video OF een comment, daarom zijn beide kolommen nullable
CREATE TABLE `likes` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `user_id`       INT             NOT NULL,
    `video_id`      INT             NULL,
    `comment_id`    INT             NULL,
    `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_video_like`   (`user_id`, `video_id`),    -- voorkomt dat iemand dezelfde video 2x liked
    UNIQUE KEY `unique_comment_like` (`user_id`, `comment_id`),  -- voorkomt dat iemand dezelfde comment 2x liked
    FOREIGN KEY (`user_id`)    REFERENCES `users`    (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`video_id`)   REFERENCES `videos`   (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE
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

-- Categorieen staan los van videos zodat dezelfde categorie bij meerdere videos kan horen
CREATE TABLE `categories` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `name`          VARCHAR(100)    NOT NULL UNIQUE,

    PRIMARY KEY (`id`)
);

-- Koppeltabel voor de N:N relatie tussen videos en categorieen
CREATE TABLE `video_category` (
    `video_id`      INT             NOT NULL,
    `category_id`   INT             NOT NULL,

    PRIMARY KEY (`video_id`, `category_id`),
    FOREIGN KEY (`video_id`)    REFERENCES `videos`     (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
);
