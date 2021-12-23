CREATE TABLE `provinces` (
     `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
     `code` varchar(255) NOT NULL,
     `name` varchar(255) NOT NULL,
     `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`) USING BTREE
);
