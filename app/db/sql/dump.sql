-- Cria tabela de usuario
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(250) NOT NULL,
  `endereco` varchar(250) DEFAULT NULL,
  `cliente` char(1) DEFAULT NULL,
  `administrador` char(1) DEFAULT NULL,
  `data_cadastro` date DEFAULT current_timestamp(),
  `ativo` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert de dados iniciais
INSERT INTO `user` (`id`, `nome`, `cpf`, `email`, `senha`, `endereco`, `cliente`, `administrador`, `data_cadastro`, `ativo`) VALUES
(8, 'Administrador', '12345678999', 'adm@adm.com', '$2y$10$JVJMZMN.Krw3aBV3eBcv0ubYTOoYgFrIeduy6zTfBX1YDpxDI73ge', 'IÃ§ara - SC', '', 'S', '2021-05-22', 'S'),
(9, 'Cliente', '12345612345', 'cliente@cliente.com', '$2y$10$RyHvC3SgvBk5JlUfIjO/meqYaFoudgqZ0Eh5b5P2zdwQpUBE9v8TK', 'AraranguÃ¡ - SC', 'S', '', '2021-05-22', 'S');

-- Cria os indices
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

-- Configura a primary key
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

-----------------------------------------------------------------------------------------
-- Cria tabela de produto
CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `arquivo` varchar(200) DEFAULT NULL,
  `descricao` varchar(400) NOT NULL,
  `valor` double NOT NULL,
  `data_cadastro` date DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert de dados iniciais
INSERT INTO `product` (`id`, `nome`, `arquivo`, `descricao`, `valor`, `data_cadastro`, `id_user`) VALUES
(15, 'Demonâ€™s Souls', '60a8379599824.jpg', 'O remake de Demonâ€™s Souls, tÃ­tulo da From Software lanÃ§ado originalmente em 2009 para PlayStation 3 (PS3), Ã© um dos jogos mais aguardados do ano. ', 55, '2021-05-21', 8),
(16, 'Marvelâ€™s Spider-Man: Miles Morales', '60a837cfd2caa.jpg', 'Com lanÃ§amento previsto tanto para PS4 como PS5, Marvelâ€™s Spider-Man: Miles Morales Ã© um jogo standalone baseado no Marvelâ€™s Spider-Man de 2018, tÃ­tulo bastante aclamado pela crÃ­tica e pelos fÃ£s. ', 120, '2021-05-21', 8),
(17, 'Sackboy: A Big Adventure', '60a8385d32460.jpeg', 'A franquia LittleBigPlanet sempre foi conhecida por permitir a criaÃ§Ã£o de fases e mecÃ¢nicas.', 200, '2021-05-21', 9),
(18, 'Astroâ€™s Playroom', '60a8388d3dce3.jpg', 'Por falar em mascote, com o lanÃ§amento do PS5, a Sony parece ter grandes planos para o personagem Astro: todos os jogadores terÃ£o acesso ao game Astroâ€™s Playroom, que virÃ¡ prÃ©-instalado no novo console. ', 180, '2021-05-21', 9);


-- Cria os indices
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_fk_user` (`id_user`);

-- Configura a primary key
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;  

-- Configura a foreing key
ALTER TABLE `product`
  ADD CONSTRAINT `id_fk_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);
COMMIT;  

-----------------------------------------------------------------------------------------------

-- Cria tabela de pedido
CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `valor_total` double NOT NULL,
  `data` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cria os indices
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`);

-- Configura a primary key
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


-----------------------------------------------------------------------------------------------

-- Cria tabela de pedido_produto
CREATE TABLE `pedido_produto` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Adiciona index
ALTER TABLE `pedido_produto`
  ADD PRIMARY KEY (`id`);

-- Adiciona a primary key 
ALTER TABLE `pedido_produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

-- Configura a foreing key
ALTER TABLE `pedido_produto`
  ADD CONSTRAINT `id_fk_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`),
  ADD CONSTRAINT `id_fk_produto` FOREIGN KEY (`id_produto`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `id_fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `user` (`id`);
COMMIT;