INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `user_creator_id`, `user_updater_id`, `created_at`, `updated_at`) VALUES
(2, 'Andris', 'admin@gmail.com', '2021-07-08 06:47:45', '$2y$10$U4zsNn5aYQh/phQz3vjDk.7ZNsi8k4q5pF6F0bJkRTBMysG9S7MA6', NULL, 2, NULL, '2021-07-08 06:47:45', '2021-07-08 06:47:45'),
(3, 'Admin', 'andrisdwimartono@gmail.com', '2021-07-08 06:56:59', '$2y$10$0gurVTr2O3zAgtB13x7hv.iSoq0LSM9p9Xrv8E75zUaHgjmOgCRk.', NULL, 2, NULL, '2021-07-08 06:56:59', '2021-07-08 06:56:59');

INSERT INTO `menus` (`id`, `mp_sequence`, `m_sequence`, `menu_name`, `url`, `menu_icon`, `parent_id`, `is_group_menu`, `is_shown_at_side_menu`, `user_creator_id`, `user_updater_id`, `created_at`, `updated_at`) VALUES
(1, 2, 0, 'Settings', '#', 'fa-cogs', NULL, 'on', 'on', 2, NULL, NULL, '2021-07-14 22:28:52'),
(2, 2, 1, 'Menu', 'menu', 'fa-bars', 1, NULL, 'on', 2, NULL, NULL, '2021-07-14 22:28:52'),
(3, 2, 5, 'User', 'user', 'fa-user', 1, NULL, 'on', 2, NULL, NULL, '2021-07-14 22:28:52'),
(4, 3, 0, 'Master', '#', 'fa-database', NULL, 'on', 'on', 2, NULL, NULL, '2021-07-14 17:54:24'),
(8, 1, 0, 'Home', 'home', 'fa-home', NULL, NULL, 'on', 2, NULL, NULL, NULL),
(10, 2, 2, 'Create Menu', 'createmenu', 'fa-bars', 1, NULL, NULL, 2, NULL, NULL, '2021-07-14 22:28:52'),
(11, 2, 4, 'Show Menu', 'menu/{id}', 'fa-bars', 1, NULL, NULL, 2, NULL, NULL, '2021-07-14 22:28:52'),
(12, 2, 3, 'Edit Menu', 'menu/{id}/edit', 'fa-bars', 1, NULL, NULL, 2, NULL, NULL, '2021-07-14 22:28:52'),
(13, 2, 6, 'Create User', 'createuser', '', 1, NULL, NULL, 2, NULL, '2021-07-14 14:40:54', '2021-07-14 22:28:52'),
(14, 2, 7, 'Edit User', 'user/{id}/edit', '', 1, NULL, NULL, 2, NULL, '2021-07-14 14:42:37', '2021-07-14 22:28:52'),
(15, 2, 8, 'Show User', 'user/{id}', '', 1, NULL, NULL, 2, NULL, '2021-07-14 14:42:37', '2021-07-14 22:28:52'),
(16, 2, 9, 'Assign Menu', 'assignmenu/{id}/edit', '', 1, NULL, NULL, 2, NULL, '2021-07-14 22:28:52', '2021-07-14 22:28:52');

INSERT INTO `user_menus` (`id`, `user_id`, `menu_id`, `is_granted`, `mp_sequence`, `m_sequence`, `menu_name`, `url`, `menu_icon`, `parent_id`, `is_group_menu`, `is_shown_at_side_menu`, `user_creator_id`, `user_updater_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'on', 2, 0, 'Settings', '#', 'fa-cogs', NULL, 'on', 'on', 2, NULL, NULL, '2021-07-14 22:28:52'),
(2, 2, 2, 'on', 2, 1, 'Menu', 'menu', 'fa-bars', 1, NULL, 'on', 2, NULL, NULL, '2021-07-14 22:28:52'),
(3, 2, 3, 'on', 2, 5, 'User', 'user', 'fa-user', 1, NULL, 'on', 2, NULL, NULL, '2021-07-14 22:28:52'),
(4, 2, 4, 'on', 3, 0, 'Master', '#', 'fa-database', NULL, 'on', 'on', 2, NULL, NULL, '2021-07-14 17:54:24'),
(5, 2, 8, 'on', 1, 0, 'Home', 'home', 'fa-home', NULL, NULL, 'on', 2, NULL, NULL, NULL),
(6, 2, 10, 'on', 2, 2, 'Create Menu', 'createmenu', 'fa-bars', 1, NULL, NULL, 2, NULL, NULL, '2021-07-14 22:28:52'),
(7, 2, 11, 'on', 2, 4, 'Show Menu', 'menu/{id}', 'fa-bars', 1, NULL, NULL, 2, NULL, NULL, '2021-07-14 22:28:52'),
(8, 2, 12, 'on', 2, 3, 'Edit Menu', 'menu/{id}/edit', 'fa-bars', 1, NULL, NULL, 2, NULL, NULL, '2021-07-14 22:28:52'),
(9, 2, 13, 'on', 2, 6, 'Create User', 'createuser', '', 1, NULL, NULL, 2, NULL, '2021-07-14 14:40:54', '2021-07-14 22:28:52'),
(10, 2, 14, 'on', 2, 7, 'Edit User', 'user/{id}/edit', '', 1, NULL, NULL, 2, NULL, '2021-07-14 14:42:37', '2021-07-14 22:28:52'),
(11, 2, 15, 'on', 2, 8, 'Show User', 'user/{id}', '', 1, NULL, NULL, 2, NULL, '2021-07-14 14:42:37', '2021-07-14 22:28:52'),
(12, 2, 16, 'on', 2, 9, 'Assign Menu', 'assignmenu/{id}/edit', '', 1, NULL, NULL, 2, NULL, '2021-07-14 22:28:52', '2021-07-14 22:28:52');
