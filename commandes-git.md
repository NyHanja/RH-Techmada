# Commandes Git utiles

## Voir la branche actuelle

```bash
git branch --show-current
```

## Mettre à jour la branche locale

```bash
git pull
```

Si tu veux aussi récupérer toutes les infos du dépôt distant sans fusionner :

```bash
git fetch
```

## Créer une nouvelle branche

```bash
git checkout -b nom-de-la-branche
```

Ou avec la commande moderne :

```bash
git switch -c nom-de-la-branche
```

## Changer de branche

```bash
git checkout nom-de-la-branche
```

Ou avec la commande moderne :

```bash
git switch nom-de-la-branche
```

## Supprimer une branche

Supprimer une branche locale :

```bash
git branch -d nom-de-la-branche
```

Forcer la suppression si Git refuse :

```bash
git branch -D nom-de-la-branche
```

## Envoyer les changements sur le dépôt distant

```bash
git push
```

Pour publier une nouvelle branche :

```bash
git push -u origin nom-de-la-branche
```

## Récupérer les changements distants

```bash
git pull
```

Pour récupérer sans fusionner immédiatement :

```bash
git fetch
```

## Fusionner une branche avec une autre

Se placer sur la branche cible, puis fusionner l'autre branche dedans :

```bash
git switch branche-cible
git merge branche-a-fusionner
```

## Fusionner en cas de conflit

Si Git signale un conflit :

```bash
git status
```

Puis ouvrir les fichiers en conflit, corriger les marques de conflit, puis :

```bash
git add .
git commit
```

Si tu veux annuler la fusion en cours :

```bash
git merge --abort
```

## Résumé rapide

```bash
git branch --show-current
git pull
git switch -c nouvelle-branche
git switch autre-branche
git branch -d ancienne-branche
git push -u origin nouvelle-branche
git merge branche-a-fusionner
```