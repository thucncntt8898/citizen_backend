CREATE TABLE `occupations` (
     `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
     `name` varchar(255) NOT NULL,
     `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`) USING BTREE
);
