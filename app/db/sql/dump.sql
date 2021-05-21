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
(5, 'Admin', '12345678999', 'adm@adm.com', '$2y$10$igkqFXZt20W4t.RmkyoEo.hQPIAAq4zbikEyVjl7P1qngbN2b2Zni', '', 'S', 'S', '2021-05-21', 'S'),
(6, 'Cliente', '12345612345', 'cliente@cliente.com', '$2y$10$f1iuwYDvKYbDAq4wwsEzaeam9r619DJRAiR6KHgCn9dfil.weMOmS', '', 'S', '', '2021-05-21', 'S');

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
(12, 'Produto cliente', '60a756b8de11d.png', 'dasfasfasfas', 10, '2021-05-21', 6),
(14, 'Produto cliente 2', '60a75e598ef4f.jpg', 'sssssssssssssssss', 55, '2021-05-21', 6);


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