-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/06/2025 às 00:27
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projeto_jv`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `estoque` int(11) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `descricao` longtext NOT NULL,
  `video` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `preco`, `imagem`, `estoque`, `categoria`, `descricao`, `video`) VALUES
(31, 'Apple MacBook Air M1', 9999.00, 'macbook_air_m1.jpg', 30, 'estudos', '', ''),
(32, 'Dell Inspiron 15 3000', 2999.00, 'dell_inspiron_15_3000.jpg', 35, 'custo-benefício', '', ''),
(33, 'Lenovo IdeaPad 1 14', 2499.00, 'lenovo_ideapad_1_14.jpg', 32, 'custo-benefício', '', ''),
(34, 'HP 15s-fq2717nr', 2699.00, 'hp_15s_fq2717nr.jpg', 28, 'custo-benefício', '', ''),
(35, 'Asus VivoBook 15', 3899.00, 'asus_vivobook_15.jpg', 24, 'estudos', '', ''),
(36, 'Asus VivoBook 14', 3199.00, 'asus_vivobook_14.jpg', 26, 'estudos', '', ''),
(37, 'Dell XPS 13 9300', 10999.00, 'dell_xps_13_9300.jpg', 14, 'premium', '', ''),
(38, 'Lenovo IdeaPad 3 14', 3999.00, 'lenovo_ideapad_3_14.jpg', 28, 'estudos', '', ''),
(39, 'Acer Aspire 5', 3499.00, 'acer_aspire_5.jpg', 26, 'custo-benefício', '', ''),
(40, 'HP Pavilion 14', 4299.00, 'hp_pavilion_14.jpg', 19, 'estudos', '', ''),
(41, 'Samsung Notebook Flash', 3299.00, 'samsung_notebook_flash.jpg', 24, 'custo-benefício', '', ''),
(42, 'Microsoft Surface Laptop Go', 4999.00, 'surface_laptop_go.jpg', 16, 'estudos', '', ''),
(43, 'Samsung Galaxy Book Go', 2799.00, 'samsung_galaxy_book_go.jpg', 27, 'estudos', '', ''),
(44, 'Asus ZenBook Flip 13', 8999.00, 'asus_zenbook_flip_13.jpg', 9, '2 em 1', '', ''),
(45, 'HP Spectre x360 14', 11999.00, 'hp_spectre_x360_14.jpg', 13, '2 em 1', '', ''),
(46, 'Dell XPS 13 9310', 10999.00, 'dell_xps_13_9310.jpg', 15, 'premium', '', ''),
(47, 'Microsoft Surface Pro 8', 9999.00, 'surface_pro_8.jpg', 8, '2 em 1', '', ''),
(48, 'HP Spectre x360 16', 16499.00, 'hp_spectre_x360_16.jpg', 6, '2 em 1', '', ''),
(49, 'Lenovo Yoga 7i', 7999.00, 'lenovo_yoga_7i.jpg', 11, '2 em 1', '', ''),
(50, 'Asus ZenBook 14 UX425EA', 8499.00, 'asus_zenbook_14_ux425ea.jpg', 20, 'premium', '', ''),
(51, 'Acer Swift 3 SF314-511', 6799.00, 'acer_swift_3_sf314_511.jpg', 18, 'custo-benefício', '', ''),
(52, 'Lenovo ThinkPad X1 Nano', 15999.00, 'thinkpad_x1_nano.jpg', 4, 'premium', '', ''),
(53, 'Dell XPS 15 9520', 17499.00, 'dell_xps_15_9520.jpg', 5, 'premium', '', ''),
(54, 'LG Gram 16Z90Q', 12999.00, 'lg_gram_16z90q.jpg', 11, 'premium', '', ''),
(55, 'LG Gram 17Z90Q', 15999.00, 'lg_gram_17z90q.jpg', 8, 'premium', '', ''),
(56, 'Dell G5 15 SE', 8999.00, 'dell_g5_15_se.jpg', 0, 'gamer', '', ''),
(57, 'Lenovo Legion 5 Pro', 11999.00, 'lenovo_legion_5_pro.jpg', 18, 'gamer', '', ''),
(58, 'MSI GF63 Thin', 6499.00, 'msi_gf63_thin.jpg', 25, 'gamer', '', ''),
(59, 'Acer Predator Helios 300', 10999.00, 'acer_predator_helios_300.jpg', 15, 'gamer', '', ''),
(61, 'Razer Blade Stealth 13', 13999.00, 'razer_blade_stealth_13.jpg', 6, 'gamer', '', ''),
(62, 'Razer Blade 15 Advanced', 15999.00, 'razer_blade_15_advanced.jpg', 10, 'gamer', '', ''),
(63, 'Gigabyte Aorus 15G', 13999.00, 'gigabyte_aorus_15g.jpg', 12, 'gamer', '', ''),
(64, 'Gigabyte Aero 15 OLED', 14999.00, 'gigabyte_aero_15_oled.jpg', 4, 'premium', '', ''),
(65, 'Microsoft Surface Laptop Studio', 14999.00, 'surface_laptop_studio.jpg', 5, '2 em 1', '', ''),
(66, 'Lenovo ThinkPad X1 Carbon Gen 11', 13999.00, 'thinkpad_x1_carbon_gen11.jpg', 8, 'premium', '', ''),
(72, 'Acer Nitro V15', 4799.00, '685b2b216ffda.jpg', 7, 'gamer', '', ''),
(73, 'ehteag', 314.00, '685b2e22c990b.png', 13213, 'gamer', 'Resident Evil 4 é um jogo eletrônico de survival horror e ação em terceira pessoa. Ele foi originalmente lançado em 2005 para o GameCube e posteriormente recebeu um remake em 2023, com gráficos reimaginados, jogabilidade atualizada e um enredo reimaginado, mas mantendo a essência do original. O jogo acompanha o agente Leon S. Kennedy em uma missão para resgatar a filha do presidente, Ashley Graham, que foi sequestrada e levada para uma vila isolada na Europa, onde algo terrível está acontecendo com os habitantes.  Em resumo, o jogo envolve: Resgate da filha do presidente: Leon precisa encontrar e resgatar Ashley, que foi sequestrada por uma seita.  Enfrentamento de inimigos infectados: Leon enfrenta hordas de inimigos infectados por um parasita que controla suas mentes, diferentes dos zumbis clássicos de Resident Evil.  Um ambiente europeu isolado: A história se passa em uma vila rural e um castelo na Europa, oferecendo um novo cenário para a franquia.  Combate e gerenciamento de recursos: O jogo combina combate intenso com gerenciamento de itens, incluindo melhorias de armas e uso de ataques corpo a corpo e contra-ataques.  Novas mecânicas: O remake introduz novas mecânicas como aparar com a faca e mira livre, permitindo atirar em partes específicas do corpo dos inimigos.  O remake de 2023 também oferece: Gráficos e jogabilidade atualizados: O jogo foi totalmente reimaginado com gráficos detalhados e mecânicas de jogo mais modernas. Modos de jogo adicionais: O jogo inclui modos como \"Os Mercenários\" e \"Caminhos Distintos\" que expandem a experiência. Compatibilidade com VR: Uma versão para PlayStation VR2 permite uma experiência imersiva com gráficos detalhados e manejo realista de armas. ', 'JGYtXCiSOO8'),
(74, 'Acer Nitro V15 ANV15-51-57WS', 5226.14, 'https://img.youtube.com/vi/kx4cO_BAFUU/maxresdefault.jpg', 15, 'gamer', 'Notebook Gamer Acer Nitro V15 ANV15-51-57WS: Intel® Core™ i5-13420H de 13ª geração, 8 GB DDR5, 512 GB SSD, NVIDIA GeForce RTX 3050 GDDR6, tela 15,6″ FHD 144 Hz, Windows 11 Home.', 'kx4cO_BAFUU'),
(75, 'Dell XPS 13 9345', 7199.00, 'https://img.youtube.com/vi/82DxJXXYm28/maxresdefault.jpg', 10, 'premium', 'Ultraportátil Dell XPS 13 9345: Qualcomm® Snapdragon® X Elite, 16 GB LPDDR5X, 1 TB SSD, tela 13,4″ Touch 3K OLED 120 Hz, Windows 11 Pro, design em alumínio usinado CNC.', '82DxJXXYm28'),
(76, 'ASUS ZenBook 14 UM3406HA', 14516.00, 'https://img.youtube.com/vi/KkCi5jEjADs/maxresdefault.jpg', 20, 'custo-benefício', 'ASUS ZenBook 14 UM3406HA: AMD Ryzen™ 7 8840HS 8-core, 16 GB LPDDR5, 512 GB SSD, tela 14″ WUXGA OLED, Windows 11 Pro, ultrafino (1,19 kg) e metal escovado.', 'KkCi5jEjADs'),
(77, 'Microsoft Surface Pro 9', 8999.00, 'https://img.youtube.com/vi/oMu-E3knVHU/maxresdefault.jpg', 12, '2 em 1', 'Microsoft Surface Pro 9: Processadores Intel® Core™ de 12ª geração, até 32 GB RAM, até 1 TB SSD, tela PixelSense® de 13″, compatível com Surface Slim Pen 2, Windows 11.', 'oMu-E3knVHU'),
(78, 'Lenovo IdeaPad Flex 5 14ABR8', 4299.00, 'https://img.youtube.com/vi/3G8QJeaT4i8/maxresdefault.jpg', 18, 'estudos', 'Lenovo IdeaPad Flex 5 14ABR8: AMD Ryzen™ 5 4500U 6-core, 16 GB DDR4, 512 GB SSD, tela 14″ Full HD touchscreen 360°, teclado retroiluminado, Windows 11 Home.', '3G8QJeaT4i8');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'vitin', '4917victorgabrielfbarbosa@gmail.com', '$2y$10$xU5VkIUFB5zcY0LVyO0.duOW9d91hqPdCy1BqnXH1wkquGZa5RjAu'),
(2, 'José', 'despairsray10@gmail.com', '$2y$10$orOvpR7.sITlOpYe/N1uF.8oke.ehaxbYSEZwkh.kQSncPB4Ct4Jm'),
(3, 'junior', 'junior123@gmail.com', '$2y$10$6H0T/HtnooTi2wyd2phhReaSuC3uZJM.45djBnGADDKLpN/IUkboe'),
(4, '3fghtseh', 'uygy@vhbjk.com', '$2y$10$v59stvFDEemdnuixCYPzZebt34nojX0nuIpi8BS4Lxu2gblH/MVbe'),
(5, '3fghtseh', 'uygy@gmail.com', '$2y$10$wlNdZFYX9KVJWBazCylYG.48trhvSPzOm0HomonbZIkJKUVRn0koS'),
(9, 'teste1', 'teste1@gmail.com', '$2y$10$FfHd9qBnQnHH8xGcikk4t.8igbnQMsdd1pc1JjsRqX4vJBmHQh60G'),
(11, 'teste2', 'teste2@gmail.com', '$2y$10$sYJ3elBmjHJy6wTrdzaeSOmOnu9Hjtql6mYUe5bj.aC34ThL8rrHG'),
(12, 'hhgwrgrw', 'hhgwrgrw@gmail.com', '$2y$10$JBk6x2vQUpZWt7ST0sTd/eVuL0eRkac3Pr3V9A7Y1s/E8zm.xJuvG');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
