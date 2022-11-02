# Changelog

## 05 July 2017: Version 2.2.2
 * [FIX] add keywords to details

## 27 June 2017: Version 2.2.1
 * [FIX] fix error on index due to translation

## 19 June 2017: Version 2.2.0
 * [FEATURE] cli command to transform a language to another

## 08 June 2017: Version 2.1.2
 * [FIX] add more label class + fix 2 translations error.

## 08 June 2017: Version 2.1.1
 * [FIX] add translations

## 06 June 2017: Version 2.1.0
 * [FEATURE] major adjustments to indexation to provide valid indexation to the future release of the new soprema.ca
 * [FIX] various fix regarding companies and attributes.

## 25 May 2017: Version 2.0.17
 * [FIX] Fix import child 

## 24 May 2017: Version 2.0.16
 * [FIX] Fix import parents 

## 17 May 2017: Version 2.0.15
 * [FIX] Ne plus supprimer les fichiers des images lors de la suppression des images pcq elles sont utilisés pour plusieurs compagnies.

## 17 mai 2017: Version 2.0.14
 * [FIX] Fix traduction du champ choice_multiple et cleanups

## 17 mai 2017: Version 2.0.13
 * [FIX] Redirige à l'accueil après le changement de compagnie
 * [FIX] Utilise les même traductions partout pour les types d'attributs
 * [FIX] Quelques fix pour les attributs par défaut lors de la création de compagnie

## 12 mai 2017: Version 2.0.11
 * [FIX] add fix regarding user with no active company.

## 11 mai 2017: Version 2.0.10
 * [FIX] Error on related products for inline addition.

## 11 mai 2017: Version 2.0.9
 * [FIX] Error on related products.

## 31 mars 2017: Version 2.0.8
 * [FEATURE] #64598 - Add converted units.

## 16 mars 2017: Version 2.0.7
 * [FIX] RM63989 - Exportation de produits - Ajuster index + aller chercher les produits complémentaires

## 07 février 2017: Version 2.0.6
 * [FIX] fix images files / path into api

## 07 février 2017: Version 2.0.5
 * [FIX] fix system media route in api

## 07 février 2017: Version 2.0.4
 * [FIX] fix system

## 03 février 2017: Version 2.0.3
 * [FIX] RM63321 - Ajustement script d'exportation

## 02 février 2017: Version 2.0.2
 * [FIX] prevent 500 when elasticsearch cant find the data

## 02 février 2017: Version 2.0.1
 * [FIX] remove min-height it affects too many field not intended to do that

## 02 février 2017: Version 2.0.0
 * [FEATURE] PIM version international

## 19 décembre 2016: Version 1.3.13
 * [FEATURE] RM61776 - Ajouter un nouvel attibut "Brochure Web" aux produits parent

## 16 Décembre 2016: Version 1.3.12
 * [FIX] attribute save

## 14 Décembre 2016: Version 1.3.11
 * RM 60402: Exportation: Corriger un bug avec le nom des produits parent

## 13 Décembre 2016: Version 1.3.10
 * [FIX] fix broken field.

## 6 Décembre 2016: Version 1.3.9
 * RM 60402: Exportation: Mettre un limit à la requete sql elastic search pour bypasser le default limit (200)

## 25 Novembre 2016: Version 1.3.8
 * RM 60402: Ajustement au script pour ajouter une nouvelle exportation pour trier les colonnes

## 24 Novembre 2016: Version 1.3.7
 * RM 60402 et 60399: Ajustement au script d'exportation pour afficher le role des produits dans les produits enfants également
 
## 24 Novembre 2016: Version 1.3.6
 * RM 60402 et 60399: Ajustement au script d'exportation pour afficher la liste des composantes dans les produits enfants également

## 10 Novembre 2016: Version 1.3.5
 * RM 60402 et 60399: Oubli - Ajout du dossier exportation + ajustement du gitignore

## 10 Novembre 2016: Version 1.3.4
 * RM 60402 et 60399: Ajout de scripts d'exportation csv pour les produits

## 3 Novembre 2016: Version 1.3.3
 * Ajouter un type d'attribut "Text without translation" pour pouvoir ajouter des code alphanumérique

## 1 Septembre 2016: Version 1.3.2
 * Fix la modif d'attribut pour les attributs qui n'ont pas de values

## 01 Aout 2016: Version 1.3.1
 * [FIX] ajout d'un use manquant.

## 01 Aout 2016: Version 1.3.0
 * [FEATURE] add fuzziness in fields
 * [FIX] attributes bug related to fountains and ponds attributes
 * [FIX] add no space name field to the 3 other models.
 * [FIX] adjust readme to include the sync instructions.
 * [FIX] add missing npm package + adjust readme.
 * [FIX] Ajout d'un champ qui aide à faire la recherche fuzzy de systèmes et de produits
 * [FIX] Si on est en local, utilise une image générique pour créer le thumbnail ce qui fait que pu besoin d'aller rsync les images du prod

## 11 Juillet 2016: Version 1.2.4
 * [FIX] Migration et config pour les failed_jobs dans la bd

## 08 Juillet 2016: Version 1.2.3
 * [FIX] Caster en array
 * [FIX] Correction de la job.
 * [FIX] fix potentiel pour les event

## 06 Juillet 2016: Version 1.2.2
 * [FIX] Update config pour version de 0.5.9 qui n'a pu de closure, permettant ainsi de faire config:cache

## 06 Juillet 2016: Version 1.2.1
 * [FIX] Fontaines et bassins dépassait dans le menu

## 06 Juillet 2016: Version 1.2.0
 * [FEATURE] Ajout de Fontaines et bassins aux catégories de systèmes

## 05 Juillet 2016: Version 1.1.1
 * [FIX] ajout d'un petit fix pour s'assuré que le code ne timeout pas sur un changement dans une liste de choix sur un attribut

## 20 Juin 2016: Version 1.1.0
 * [FIX] Ajout de star_product et de new_product au schéma pour que ca plante pas lors du sort si aucun produit en vedette ou nouveau
 * Déplacé deploy dans dossier release
 * [FEATURE] Ajout un "multi-field" (https://www.elastic.co/guide/en/elasticsearch/reference/2.3/multi-fields.html) sur le name qui est un string not_analyzed pour pouvoir faire un sort alphabétique
 * Ajout de new product et star product dans l'index ES
 * [FEATURE] RM #56704 #56714 Ajout de checkbox "Produit vedette" et "Nouveau produit" dans la création/modif de parent et enregistrement direct dans la table parent_products

## 03 Juin 2016: Version 1.0.4
 * [FIX] L'importation des terrasses ne fonctionnait pas.

## 02 Juin 2016: Version 1.0.3
 * [FIX] ajout de traduction pour les units

## 01 Juin 2016: Version 1.0.2
 * [FIX] fix possible error on ajax by disabling the button

## 31 Mai 2016: Version 1.0.1
 * Config deployer: déploie le master en preprod et supprime la branche en prod vu qu'on devrais juste déployer des tags.
 * [FIX] Menu plantant dans la création d'attributs systèmes de type autre que global (/attribute/create/system/roof_system)

## 31 Mai 2016: Version 1.0.0

 * Première version déployée en production sur pim.soprema.ca
