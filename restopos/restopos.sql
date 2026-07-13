

CREATE TABLE `categorie` (
  `id_categorie` int UNSIGNED NOT NULL,
  `libelle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `libelle`) VALUES
(4, 'Boissons'),
(5, 'Cafés & Thés'),
(2, 'Entrées'),
(3, 'Pizzas'),
(1, 'Plats principaux');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int UNSIGNED NOT NULL,
  `numero` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `heure_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `montant_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id_table` int UNSIGNED NOT NULL,
  `id_utilisateur` int UNSIGNED NOT NULL,
  `id_statut_commande` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `numero`, `heure_creation`, `montant_total`, `id_table`, `id_utilisateur`, `id_statut_commande`) VALUES
(1, '#0042', '2026-07-09 12:14:00', 28500.00, 2, 4, 2),
(2, '#0043', '2026-07-09 12:31:00', 15200.00, 3, 5, 3),
(3, '#0044', '2026-07-09 13:05:00', 52400.00, 7, 4, 2),
(4, '#0045', '2026-07-09 13:42:00', 19800.00, 9, 6, 1),
(5, '#0046', '2026-07-09 14:10:00', 34600.00, 14, 6, 4),
(6, '#0041', '2026-07-09 11:58:00', 22100.00, 11, 4, 4),
(7, '#0047', '2026-07-11 15:51:29', 1500.00, 13, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_commande`
--

CREATE TABLE `ligne_commande` (
  `id_ligne_commande` int UNSIGNED NOT NULL,
  `quantite` smallint UNSIGNED NOT NULL DEFAULT '1',
  `prix_unitaire` decimal(10,2) NOT NULL,
  `id_commande` int UNSIGNED NOT NULL,
  `id_produit` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ligne_commande`
--

INSERT INTO `ligne_commande` (`id_ligne_commande`, `quantite`, `prix_unitaire`, `id_commande`, `id_produit`) VALUES
(1, 2, 7500.00, 1, 1),
(2, 2, 1500.00, 1, 7),
(3, 2, 5500.00, 2, 4),
(4, 1, 1500.00, 2, 7),
(5, 4, 6500.00, 3, 2),
(6, 3, 1500.00, 3, 7),
(7, 2, 8000.00, 4, 3),
(8, 2, 3500.00, 5, 5),
(9, 2, 8000.00, 5, 3),
(10, 1, 1500.00, 5, 7),
(11, 2, 5000.00, 6, 6),
(12, 1, 1500.00, 6, 7),
(13, 1, 1500.00, 7, 7);

-- --------------------------------------------------------

--
-- Structure de la table `mode_paiement`
--

CREATE TABLE `mode_paiement` (
  `id_mode_paiement` int UNSIGNED NOT NULL,
  `libelle` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mode_paiement`
--

INSERT INTO `mode_paiement` (`id_mode_paiement`, `libelle`, `couleur`) VALUES
(1, 'Espèces', '#f97316'),
(2, 'Carte bancaire', '#3b82f6'),
(3, 'Mobile Money', '#10b981'),
(4, 'Chèque', '#8b5cf6');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int UNSIGNED NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT '1',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_categorie` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `nom`, `prix`, `disponible`, `image_url`, `id_categorie`) VALUES
(1, 'Poulet braisé', 7500.00, 1, 'https://images.unsplash.com/photo-1598103442097-8b74394b95c7?w=400&h=300&fit=crop&auto=format', 1),
(2, 'Thiéboudienne', 6500.00, 1, 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=400&h=300&fit=crop&auto=format', 1),
(3, 'Mafé bœuf', 8000.00, 1, 'https://images.unsplash.com/photo-1547592180-85f173990554?w=400&h=300&fit=crop&auto=format', 1),
(4, 'Attiéké poisson', 5500.00, 1, 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=300&fit=crop&auto=format', 1),
(5, 'Salade niçoise', 3500.00, 1, 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=300&fit=crop&auto=format', 2),
(6, 'Pizza margherita', 5000.00, 1, 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400&h=300&fit=crop&auto=format', 3),
(7, 'Jus de gingembre', 1500.00, 1, 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=400&h=300&fit=crop&auto=format', 4),
(8, 'Café espresso', 1200.00, 1, 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=400&h=300&fit=crop&auto=format', 5),
(9, 'burger', 20000.00, 1, 'assets/images/prod_6a5220790049b2.09088286.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int UNSIGNED NOT NULL,
  `libelle` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur_fond` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur_texte` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `libelle`, `couleur_fond`, `couleur_texte`, `couleur`) VALUES
(1, 'Administrateur', '#ede9fe', '#7c3aed', '#8b5cf6'),
(2, 'Gérant', '#dbeafe', '#1d4ed8', '#3b82f6'),
(3, 'Caissier', '#fef3c7', '#b45309', '#f59e0b'),
(4, 'Serveur', '#d1fae5', '#047857', '#10b981');

-- --------------------------------------------------------

--
-- Structure de la table `statut_commande`
--

CREATE TABLE `statut_commande` (
  `id_statut_commande` int UNSIGNED NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur_fond` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur_texte` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `statut_commande`
--

INSERT INTO `statut_commande` (`id_statut_commande`, `code`, `libelle`, `couleur_fond`, `couleur_texte`) VALUES
(1, 'pending', 'En attente', '#fef3c7', '#b45309'),
(2, 'preparing', 'En préparation', '#dbeafe', '#1d4ed8'),
(3, 'ready', 'Prêt à servir', '#d1fae5', '#047857'),
(4, 'served', 'Servi', '#f3f4f6', '#6b7280'),
(5, 'cancelled', 'Annulé', '#fee2e2', '#dc2626');

-- --------------------------------------------------------

--
-- Structure de la table `statut_table`
--

CREATE TABLE `statut_table` (
  `id_statut_table` int UNSIGNED NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur_fond` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur_point` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur_texte` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `couleur_bordure` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `statut_table`
--

INSERT INTO `statut_table` (`id_statut_table`, `code`, `libelle`, `couleur_fond`, `couleur_point`, `couleur_texte`, `couleur_bordure`) VALUES
(1, 'available', 'Libre', '#ecfdf5', '#10b981', '#047857', '#a7f3d0'),
(2, 'occupied', 'Occupée', '#fff7ed', '#f97316', '#c2410c', '#fed7aa'),
(3, 'reserved', 'Réservée', '#eff6ff', '#3b82f6', '#1d4ed8', '#bfdbfe'),
(4, 'dirty', 'À nettoyer', '#f9fafb', '#9ca3af', '#6b7280', '#e5e7eb');

-- --------------------------------------------------------

--
-- Structure de la table `table_restaurant`
--

CREATE TABLE `table_restaurant` (
  `id_table` int UNSIGNED NOT NULL,
  `nom` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacite` tinyint UNSIGNED NOT NULL,
  `depuis` time DEFAULT NULL,
  `note` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_statut_table` int UNSIGNED NOT NULL,
  `id_utilisateur` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `table_restaurant`
--

INSERT INTO `table_restaurant` (`id_table`, `nom`, `capacite`, `depuis`, `note`, `id_statut_table`, `id_utilisateur`) VALUES
(1, 'T01', 2, NULL, NULL, 1, NULL),
(2, 'T02', 4, '12:14:00', NULL, 2, 4),
(3, 'T03', 4, '12:31:00', NULL, 2, 5),
(4, 'T04', 6, '14:00:00', 'Réservation Diallo', 3, NULL),
(5, 'T05', 2, NULL, NULL, 1, NULL),
(6, 'T06', 4, NULL, NULL, 4, NULL),
(7, 'T07', 8, '13:05:00', NULL, 2, 4),
(8, 'T08', 2, NULL, NULL, 1, NULL),
(9, 'T09', 4, '13:42:00', NULL, 2, 6),
(10, 'T10', 6, '19:00:00', 'Réservation Koné', 3, NULL),
(11, 'T11', 4, NULL, NULL, 1, NULL),
(12, 'T12', 2, NULL, NULL, 4, NULL),
(13, 'T13', 4, '15:51:29', NULL, 2, 1),
(14, 'T14', 6, '14:10:00', NULL, 2, 6),
(15, 'T15', 4, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `transaction_paiement`
--

CREATE TABLE `transaction_paiement` (
  `id_transaction` int UNSIGNED NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_heure` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_commande` int UNSIGNED NOT NULL,
  `id_mode_paiement` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `transaction_paiement`
--

INSERT INTO `transaction_paiement` (`id_transaction`, `montant`, `date_heure`, `id_commande`, `id_mode_paiement`) VALUES
(1, 34600.00, '2026-07-09 14:22:00', 5, 2),
(2, 22100.00, '2026-07-09 13:20:00', 6, 1);


--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int UNSIGNED NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `initiales` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `derniere_connexion` datetime DEFAULT NULL,
  `id_role` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `login`, `mot_passe`, `email`, `initiales`, `actif`, `derniere_connexion`, `id_role`) VALUES
(1, 'Moussa Traoré', 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'm.traore@restaurant.ci', 'MT', 1, '2026-07-09 18:18:58', 1),
(2, 'Fatoumata Koné', 'gerant', '0adea017a51a0224047865ad5b90b53289a93f01ef1b798ef8ae079b3c161640', 'f.kone@restaurant.ci', 'FK', 1, '2026-07-09 18:18:58', 2),
(3, 'Ibrahima Kouyaté', 'caissier', 'fc11763703dc22fedc8f7c3809a6555e21af7873a60c2d11ac623d24dd3e542e', 'i.kouyate@restaurant.ci', 'IK', 1, '2026-07-09 18:18:58', 3),
(4, 'Aminata Diallo', 'serveur', 'a688737c5798ab06c597de75f4c3b9bb19d0cee140d98879eb36710a3ed3855e', 'a.diallo@restaurant.ci', 'AD', 1, '2026-07-09 18:18:58', 4),
(5, 'Kouassi Bamba', 'kbamba', '94af10ce23fe100500bad561b00fa91a8f3119d0799b904b4d8c56ce34a34f8f', 'k.bamba@restaurant.ci', 'KB', 1, '2026-07-09 18:18:58', 4),
(6, 'Sékou Maïga', 'smaiga', '649553dbe31638f04ce09ffb3e87ab47c9ed49ed879d80238dc987dedf5665dd', 's.maiga@restaurant.ci', 'SM', 0, '2026-07-09 18:18:58', 4),
(7, 'Trust Mukari', 'admin1', '8e786c4dbcc0e9b6676a443e8540745c0787808ba5f8cb5099e2fd37e2c5f226', 'trustmozart41@gmail.com', 'TM', 0, NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `fk_commande_table` (`id_table`),
  ADD KEY `fk_commande_serveur` (`id_utilisateur`),
  ADD KEY `fk_commande_statut` (`id_statut_commande`);

--
-- Index pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD PRIMARY KEY (`id_ligne_commande`),
  ADD KEY `fk_ligne_commande` (`id_commande`),
  ADD KEY `fk_ligne_produit` (`id_produit`);

--
-- Index pour la table `mode_paiement`
--
ALTER TABLE `mode_paiement`
  ADD PRIMARY KEY (`id_mode_paiement`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `fk_produit_categorie` (`id_categorie`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `statut_commande`
--
ALTER TABLE `statut_commande`
  ADD PRIMARY KEY (`id_statut_commande`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `statut_table`
--
ALTER TABLE `statut_table`
  ADD PRIMARY KEY (`id_statut_table`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `table_restaurant`
--
ALTER TABLE `table_restaurant`
  ADD PRIMARY KEY (`id_table`),
  ADD UNIQUE KEY `nom` (`nom`),
  ADD KEY `fk_table_statut` (`id_statut_table`),
  ADD KEY `fk_table_serveur` (`id_utilisateur`);

--
-- Index pour la table `transaction_paiement`
--
ALTER TABLE `transaction_paiement`
  ADD PRIMARY KEY (`id_transaction`),
  ADD KEY `fk_transaction_commande` (`id_commande`),
  ADD KEY `fk_transaction_mode` (`id_mode_paiement`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_utilisateur_role` (`id_role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  MODIFY `id_ligne_commande` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `mode_paiement`
--
ALTER TABLE `mode_paiement`
  MODIFY `id_mode_paiement` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `statut_commande`
--
ALTER TABLE `statut_commande`
  MODIFY `id_statut_commande` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `statut_table`
--
ALTER TABLE `statut_table`
  MODIFY `id_statut_table` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `table_restaurant`
--
ALTER TABLE `table_restaurant`
  MODIFY `id_table` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `transaction_paiement`
--
ALTER TABLE `transaction_paiement`
  MODIFY `id_transaction` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_serveur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `fk_commande_statut` FOREIGN KEY (`id_statut_commande`) REFERENCES `statut_commande` (`id_statut_commande`),
  ADD CONSTRAINT `fk_commande_table` FOREIGN KEY (`id_table`) REFERENCES `table_restaurant` (`id_table`);

--
-- Contraintes pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD CONSTRAINT `fk_ligne_commande` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ligne_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);

--
-- Contraintes pour la table `table_restaurant`
--
ALTER TABLE `table_restaurant`
  ADD CONSTRAINT `fk_table_serveur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `fk_table_statut` FOREIGN KEY (`id_statut_table`) REFERENCES `statut_table` (`id_statut_table`);

--
-- Contraintes pour la table `transaction_paiement`
--
ALTER TABLE `transaction_paiement`
  ADD CONSTRAINT `fk_transaction_commande` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `fk_transaction_mode` FOREIGN KEY (`id_mode_paiement`) REFERENCES `mode_paiement` (`id_mode_paiement`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `fk_utilisateur_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);
COMMIT;

