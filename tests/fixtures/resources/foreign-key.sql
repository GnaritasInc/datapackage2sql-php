create table `test_table_with_foreign_key` (
	`id` int,
	`foreign_key_col` int,
	`accessibility` text,
	primary key (`id`),
	foreign key(`foreign_key_col`) references `test_referenced_table` (`referenced_col`)
);
