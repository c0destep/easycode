CREATE TABLE `users`
(
    `id`       int(11)      NOT NULL AUTO_INCREMENT,
    `name`     varchar(20)  NOT NULL,
    `email`    varchar(150) NOT NULL,
    `password` varchar(50)  NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

INSERT INTO `users` (`id`, `name`, `email`, `password`)
VALUES (1, 'Easycode', 'easycode@codingstep.com.br', 'easy')