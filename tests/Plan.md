# Plan de test du PIM

## Produit parent

* Créer un produit sans nom
* Vérifier les valeurs et message d'erreur
* Remplir le nom et l'enregistrer et déplacer dans brouillons
* Vérifier qu'il est dans la liste
* Modifier le produit sans changements
* Modifier tous les champs et vérifier si correct
* Publier et voir si dans bon tab
* Modifier la nature du produit

## Produit enfants

* crée un produit (a venir)
* Valider qu'on est dans la page d'approbation et que le produit est listé dans "Fraichements importés"
* Approuver ce produit
* Vérifier que ce produit est dans les "Sans parent"
* Assigner un parent et vérifier si ce produit est maintenant dans Brouillons
* Remodifier le parent du produit pour être le produit parent créé auparavant
* Ajouter le Média "website" et Publier
* Vérifier que le produit est maintenant dans Publiés
* Vérifier si le produit est dans les childrens du produit parent dans l'index ES:
    http://soprema.wp.local:9200/api-fr/product/_search?pretty&q=SOPRASEAL%20LM%20203
    http://soprema.wp.local:9200/api-en/product/_search?pretty&q=SOPRASEAL%20LM%20203

## Systèmes

* Créer un système avec le champ Nom manquant
* Vérifier message d'erreur et que tous les contenus de champs sont présents
* Cocher le média "website" et Enregistrer
* Vérifier que le système n'est pas encore dans complets
* Ajouter un groupe de couche et une couche au système
* Tester la modification et la suppression de groupe et de couche
* Vérifier que le produit est maintenant dans la section complets
* Vérifier que le système est dans l'index:
    http://soprema.wp.local:9200/api-fr/system/_search?pretty&q=nom manquant
    http://soprema.wp.local:9200/api-en/system/_search?pretty&q=nom manquant

## Devis, Détails et Bulletins techniques

* Créer un produit avec le nom de manquant
* Vérifier les validations et les valeurs de champs
* Enregister avec un nom et cocher le Média "website"
* Vérifier que le produit est dans l'onglet Complets et que le produit est dans l'index ES
    http://soprema.wp.local:9200/api-fr/details/_search?pretty&q=SOPRASEAL%20LM%20203
    http://soprema.wp.local:9200/api-fr/devis/_search?pretty&q=SOPRASEAL%20LM%20203
    http://soprema.wp.local:9200/api-fr/technical_bulletins/_search?pretty&q=SOPRASEAL%20LM%20203

## Utilisateurs

* Modifier son profile
* Ajouter un nouvel utilisateur sans remplir le Prénom et vérifier les validations
* Vérifier la modification d'utilisateur
* Supprimer un utilisateur

## Permissions

* Se connecter avec un utilisateur non admin et vérifier les permissions.

## Attributs

* Créer un nouvel attribut de type "Liste de choix" pour Produit enfants
* Tester l'ajout et la suppression de choix 

## Importation

* Make sure it works
