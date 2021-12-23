CREATE TABLE `users` (
     `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
     `role` int(1) NOT NULL,
     `province_id` int(11) UNSIGNED DEFAULT NULL,
     `district_id` int(11) UNSIGNED DEFAULT NULL,
     `ward_id` int(11) UNSIGNED DEFAULT NULL,
     `hamlet_id` int(11) UNSIGNED DEFAULT NULL,
     `username` varchar(255) NOT NULL ,
     `password` varchar(255) NOT NULL ,
     `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     `time_start` datetime DEFAULT NULL ,
     `time_finish` datetime DEFAULT NULL ,
     PRIMARY KEY (`id`) USING BTREE
);

ALTER TABLE `users` ADD COLUMN `status` int(1) NOT NULL DEFAULT 1;
