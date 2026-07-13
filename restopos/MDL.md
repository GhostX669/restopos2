ROLE
 ├─ id_role (id)
 ├─ libelle
 ├─ couleur_fond
 ├─ couleur_texte
 └─ couleur
 
UTILISATEUR
 ├─ id_utilisateur (id)
 ├─ nom
 ├─ login
 ├─ mot_passe
 ├─ email
 ├─ initiales
 ├─ actif
 └─ derniere_connexion

STATUT_TABLE
 ├─ id_statut_table (id)
 ├─ code            (available / occupied / reserved / dirty)
 ├─ libelle
 ├─ couleur_fond
 ├─ couleur_point
 ├─ couleur_texte
 └─ couleur_bordure

TABLE_RESTAURANT
 ├─ id_table (id)
 ├─ nom
 ├─ capacite
 ├─ depuis
 └─ note

STATUT_COMMANDE
 ├─ id_statut_commande (id)
 ├─ code            (pending/preparing/ready/served/cancelled)
 ├─ libelle
 ├─ couleur_fond
 └─ couleur_texte

COMMANDE
 ├─ id_commande (id)
 ├─ numero
 ├─ heure_creation
 └─ montant_total

CATEGORIE
 ├─ id_categorie (id)
 └─ libelle

PRODUIT
 ├─ id_produit (id)
 ├─ nom
 ├─ prix
 ├─ disponible
 └─ image_url

LIGNE_COMMANDE
 ├─ id_ligne_commande (id)
 ├─ quantite
 └─ prix_unitaire

MODE_PAIEMENT
 ├─ id_mode_paiement (id)
 ├─ libelle
 └─ couleur

TRANSACTION
 ├─ id_transaction (id)
 ├─ montant
 └─ date_heure




 ROLE           (1,1) ────POSSEDE──── (0,n) UTILISATEUR

UTILISATEUR    (0,1) ────SERT──────  (0,n) TABLE_RESTAURANT
STATUT_TABLE   (1,1) ────QUALIFIE─── (0,n) TABLE_RESTAURANT

TABLE_RESTAURANT (1,1) ──CONCERNE──  (0,n) COMMANDE
UTILISATEUR      (1,1) ──PREND────── (0,n) COMMANDE
STATUT_COMMANDE  (1,1) ──QUALIFIE─── (0,n) COMMANDE

COMMANDE       (1,1) ────COMPORTE──  (1,n) LIGNE_COMMANDE
PRODUIT        (1,1) ────CONCERNE──  (0,n) LIGNE_COMMANDE
CATEGORIE      (1,1) ────CLASSE────  (0,n) PRODUIT

COMMANDE       (1,1) ────REGLEE_PAR─ (0,n) TRANSACTION
MODE_PAIEMENT  (1,1) ────UTILISE──── (0,n) TRANSACTION

ROLE (id_role, libelle, couleur_fond, couleur_texte, couleur)

UTILISATEUR (id_utilisateur, nom, login, mot_passe, email, initiales, actif,
             derniere_connexion, #id_role)

STATUT_TABLE (id_statut_table, code, libelle, couleur_fond, couleur_point,
              couleur_texte, couleur_bordure)

TABLE_RESTAURANT (id_table, nom, capacite, depuis, note,
                  #id_statut_table, #id_utilisateur)

STATUT_COMMANDE (id_statut_commande, code, libelle, couleur_fond, couleur_texte)

CATEGORIE (id_categorie, libelle)

PRODUIT (id_produit, nom, prix, disponible, image_url, #id_categorie)

COMMANDE (id_commande, numero, heure_creation, montant_total,
          #id_table, #id_utilisateur, #id_statut_commande)

LIGNE_COMMANDE (id_ligne_commande, quantite, prix_unitaire,
                #id_commande, #id_produit)

MODE_PAIEMENT (id_mode_paiement, libelle, couleur)

TRANSACTION (id_transaction, montant, date_heure,
             #id_commande, #id_mode_paiement)