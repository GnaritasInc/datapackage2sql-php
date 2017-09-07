create table `test_table_with_enum_column` (
	`id` int,
	`enum_col` enum('Smith', 'Johnson', 'O\'Reilly'),
	primary key (`id`)
);
