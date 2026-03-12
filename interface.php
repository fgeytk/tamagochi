<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container my-3">

        
        <div class="my-3">
            <!-- ARMAGEDDON -->
            <a href="index.php?action=armageddon" class="btn btn-secondary">BOOM</a>
            <!-- RECHERCHE DE PROVISION -->
             <a href="index.php?action=rechercherProvisions" class="btn btn-secondary">Rechercher des provisions</a>
        </div>

        <!-- POINTS -->
        <h1 class="my-3"><?= $this->points ?> Points</h1>
    
        <!-- MESSAGE -->
        <?php if (!empty($this->messages)): ?>
            <div class="alert alert-info">
                <?php foreach ($this->messages as $message): ?>
                    <div><?= $message ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->points == 0): ?>
            <a href="index.php?action=nuit" class="btn btn-secondary">Nuit</a>
        <?php else: ?>
            <!-- CREER ANIMAL -->
            <div class="my-3">
                <h2>Créer un animal</h2>
                <form action="index.php" method="get">
                    <input type="hidden" name="action" value="creerAnimal">
                    <div class="row">
                        <div class="col">
                            <input type="text" name="nom" placeholder="Nom de l'animal" class="form-control">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Créer</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>


        <div class="my-3">
            <h2>Mes animaux</h2>
            <div class="row">
            <?php foreach($this->animaux as $id => $animal): ?>

                <div class="col"> 
                    <div class="card">
                        <div class="card-body">
                            <h5><?= $animal->getNom() ?></h5>
                            <p>Âge : <?= $animal->getAge() ?></p>
                            <p>Faim : <?= $animal->getFaim() ?></p>
                            <p>Soif : <?= $animal->getSoif() ?></p>
                            <p>Humeur : <?= $animal->getHumeur() ?></p>
                            <p>Santé : <?= $animal->getSante() ?></p>
                            <a href="index.php?action=soignerAnimal&id=<?= $id ?>">Soigner</a>
                            <a href="index.php?action=carresse&id=<?= $id ?>"> Carresse</a>
                            
                            <form action="index.php" method="get">
                                <input type="hidden" name="action" value="nourrirAnimal">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <select name="provision_id" required>
                                    <?php foreach($this->provisions as $pid => $provision): ?>
                                        <option value="<?= $pid ?>">
                                            <?= $provision->getType() ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit">Nourrir</button>
                            </form>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
            <h2>Mes Provisions</h2>
            </div>
                <div class="row">
                <?php foreach($this->provisions as $id => $provision): ?>
                    <div class="col"> 
                        <div class="card">
                            <div class="card-body">
                                <h5><?= $provision->getType() ?></h5>
                                <?php $valeurs = $provision->getValeur(); ?>
                                <p>Valeur :</p>
                                <ul>
                                    <li>Soif : <?= $valeurs['soif'] ?></li>
                                    <li>Faim : <?= $valeurs['faim'] ?></li>
                                    <li>Santé : <?= $valeurs['sante'] ?></li>
                                    <li>Humeur : <?= $valeurs['humeur'] ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <!-- debug --> 
            <!-- <pre><?php print_r($this) ?></pre> -->
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
