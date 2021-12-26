CREATE TABLE `citizens` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_card` varchar(255) NOT NULL,
    `fullname` varchar(255) NOT NULL,
    `dob` date NOT NULL,
    `gender` int(1) DEFAULT 0,
    `native_address` varchar(255) NOT NULL,
    `permanent_address_province` int(11) UNSIGNED NOT NULL,
    `permanent_address_district` int(11) UNSIGNED NOT NULL,
    `permanent_address_ward` int(11) UNSIGNED NOT NULL,
    `permanent_address_hamlet` int(11) UNSIGNED NOT NULL,
    `temp_address` varchar(255) NOT NULL,
    `religion` boolean DEFAULT false ,
    `edu_level` varchar(255),
    `occupation` int(11) unsigned default 1,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`) USING BTREE
);
