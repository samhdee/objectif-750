<?php
header('Status: 301 Moved Permanently', false, 301);
header('Location: web/mywords'); ?>

<html>
  <head>
    <meta charset="utf-8">

    <meta name="description" content="Ecrire tous les jours">
    <meta name="author" content="Clara Dhaleine">
    <meta name="keywords" content="quota mots journalier quotidien ecriture ecrivain nanowrimo" />
    <meta name="category" content="Ecriture mots quotidiens" />
    <meta name="language" content="fr" />
    <meta name="identifier-url" content="https://objectif750.fr" />

    <title>Bienvenue !</title>

    <link href="https://fonts.googleapis.com/css?family=Kristi|Open+Sans:400,700|Shadows+Into+Light+Two|Open+Sans+Condensed:300,700|Lato" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/web/bundles/core/css/main.css">
  </head>
  <body>
    <header id="header" class="jumbotron" style="text-align: center; padding-bottom: 20px;">
      <h1><a href="#">Bienvenue sur Objectif 750 !</a></h1>
    </header>
    <div class="container">
      <div id="content" class="col-md-12" style="text-align: center;">
        <p>Ici se tiendra soon(tm) un site pour les écrivains en herbe qui leur fournira un environnement les encourageant à écrire un certain nombre de mots par jour (par défaut 750 mais vous mettrez bien ce que vous voudrez). Il fournira également des statistiques telles que le nombre de mot à la minute en fonction de l'heure de la journée, le nombre de jours consécutifs où le quota aura été respecté, etc.</p>
        <p><strong>Mais pour le moment, il est en développement. Vous pouvez suivre sa progression sur <a target="_blank" href="https://twitter.com/objectif750" title="piou">Twitter</a> (lui aussi en développement) si le coeur vous en dit.</strong></p>
      </div>
    </div>
  </body>
</html>