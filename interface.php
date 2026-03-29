<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container my-3">

        
        <div class="my-3">
            <!-- ARMAGEDDON -->
            <a href="index.php?action=armageddon" class="btn btn-danger-90s">*** BOOM ***</a>
            <!-- RECHERCHE DE PROVISION -->
            <a href="index.php?action=rechercherProvisions" class="btn btn-90s">~~~ Chercher des provisions ~~~</a>
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
            <a href="index.php?action=nuit" class="btn btn-90s">[ zzz Nuit zzz ]</a>
        <?php else: ?>
            <!-- CREER ANIMAL -->
            <div class="my-3 p-3 form-90s">
                <h2>*** Créer un animal ***</h2>
                <form action="index.php" method="get" class="mt-2">
                    <input type="hidden" name="action" value="creerAnimal">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <input type="text" name="nom" placeholder="Nom de l'animal" class="form-control" style="border: 2px inset #888;" required>
                        </div>
                        <div class="col-auto">
                            <select name="type" class="form-select" style="border: 2px inset #888; font-family: 'Comic Sans MS', cursive;">
                                <option value="Chat">=^.^=  Chat  — faim:30 soif:40 humeur:80</option>
                                <option value="Chien">U^.^U Chien — faim:70 soif:60 humeur:100</option>
                                <option value="Lapin">(='.'=) Lapin — faim:50 soif:30 humeur:60</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-90s">&gt;&gt;&gt; Créer &lt;&lt;&lt;</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <?php
            $animauxVivants = array_filter($this->animaux, fn($a) => $a->estVivant());
        ?>
        <?php if ($this->points >= 1 && count($animauxVivants) >= 2): ?>
            <!-- JOUER ENSEMBLE -->
            <div class="my-3 p-3 form-jeu">
                <h2>*** Faire jouer ensemble ***</h2>
                <p style="font-size:0.85em; color:#000080;">Coûte 1 point — les deux gagnent de l'humeur, mais attention aux blessures !</p>
                <form action="index.php" method="get" class="mt-1">
                    <input type="hidden" name="action" value="jouerEnsemble">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <select name="id1" class="form-select" style="border: 2px inset #888; font-family: 'Comic Sans MS', cursive;">
                                <?php foreach ($animauxVivants as $vid => $va): ?>
                                    <option value="<?= $vid ?>"><?= htmlspecialchars($va->getNom()) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto" style="font-weight:bold; color:#cc0000;">joue avec</div>
                        <div class="col-auto">
                            <select name="id2" class="form-select" style="border: 2px inset #888; font-family: 'Comic Sans MS', cursive;">
                                <?php foreach ($animauxVivants as $vid => $va): ?>
                                    <option value="<?= $vid ?>"><?= htmlspecialchars($va->getNom()) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-90s">&gt; Go ! &lt;</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($this->points >= 2 && count($animauxVivants) >= 2): ?>
            <!-- COMBAT -->
            <div class="my-3 p-3 form-combat">
                <h2>⚔️ *** ATTAQUE *** ⚔️</h2>
                <p style="font-size:0.85em; color:#880000;">Coûte <strong>2 points</strong> — 70% de chance de tuer net !</p>
                <form action="index.php" method="get" class="mt-1">
                    <input type="hidden" name="action" value="combat">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <select name="id1" class="form-select" style="border: 2px inset #880000; font-family: 'Comic Sans MS', cursive;">
                                <?php foreach ($animauxVivants as $vid => $va): ?>
                                    <option value="<?= $vid ?>"><?= htmlspecialchars($va->getNom()) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto" style="font-weight:bold; color:#cc0000; font-size:1.3em;">💀 attaque 💀</div>
                        <div class="col-auto">
                            <select name="id2" class="form-select" style="border: 2px inset #880000; font-family: 'Comic Sans MS', cursive;">
                                <?php foreach ($animauxVivants as $vid => $va): ?>
                                    <option value="<?= $vid ?>"><?= htmlspecialchars($va->getNom()) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-combat">&gt;&gt; ATTAQUER &lt;&lt;</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <div class="my-3">
            <h2>Mes animaux</h2>
            <div class="row">
            <?php foreach($this->animaux as $id => $animal): ?>
                <?php
                    $classe     = get_class($animal);
                    $typeAnimal = substr($classe, strrpos($classe, '\\') + 1);
                    $cardClass  = 'card-' . strtolower($typeAnimal);
                ?>
                <div class="col">
                    <div class="card card-90s <?= $cardClass ?>">
                        <div class="card-body">
                            <?php
                                $imgPath = 'asset/' . strtolower($typeAnimal) . '.gif';
                                $hasImg  = file_exists(__DIR__ . '/' . $imgPath);
                            ?>
                            <?php if (!$animal->estVivant()): ?>
                                <?php if ($hasImg): ?>
                                    <img src="<?= $imgPath ?>" alt="<?= $typeAnimal ?>" style="display:block; margin:auto; max-width:80px; opacity:0.35; filter: grayscale(100%);">
                                <?php else: ?>
                                    <span class="animal-symbol blink">X_X</span>
                                <?php endif; ?>
                                <h5 style="color: #888;"><?= htmlspecialchars($animal->getNom()) ?> <small>[<?= $typeAnimal ?>]</small></h5>
                                <h6>Âge : <?= $animal->getAge() ?></h6>
                                <p style="color: #cc0000; font-weight: bold; text-align: center;">-- R.I.P --</p>
                            <?php else: ?>
                                <?php if ($hasImg): ?>
                                    <img src="<?= $imgPath ?>" alt="<?= $typeAnimal ?>" style="display:block; margin:auto; max-width:80px; image-rendering: pixelated;">
                                <?php else: ?>
                                    <span class="animal-symbol"><?= htmlspecialchars($animal->getSymbol()) ?></span>
                                <?php endif; ?>
                                <h5><?= htmlspecialchars($animal->getNom()) ?> <small style="font-size: 0.7em; color: #555;">[<?= $typeAnimal ?>]</small></h5>
                                <h6>Âge : <?= $animal->getAge() ?></h6>
                                <?php
                                    $stats = [
                                        'faim'   => ['label' => 'Faim',   'val' => $animal->getFaim(),   'class' => 'bar-faim',   'css' => 'stat-faim'],
                                        'soif'   => ['label' => 'Soif',   'val' => $animal->getSoif(),   'class' => 'bar-soif',   'css' => 'stat-soif'],
                                        'humeur' => ['label' => 'Humeur', 'val' => $animal->getHumeur(), 'class' => 'bar-humeur', 'css' => 'stat-humeur'],
                                        'sante'  => ['label' => 'Santé',  'val' => $animal->getSante(),  'class' => 'bar-sante',  'css' => 'stat-sante'],
                                    ];
                                ?>
                                <?php foreach ($stats as $s): ?>
                                    <div style="font-size:0.8em;"><span class="<?= $s['css'] ?>"><?= $s['label'] ?> :</span> <?= $s['val'] ?>/100</div>
                                    <div class="stat-bar-wrap"><span class="stat-bar <?= $s['class'] ?>" style="width:<?= $s['val'] ?>%"></span></div>
                                <?php endforeach; ?>
                                <div class="mt-2">
                                <a href="index.php?action=soignerAnimal&id=<?= $id ?>" class="action-link link-soigner">[ Soigner ]</a>
                                <a href="index.php?action=carresse&id=<?= $id ?>" class="action-link link-caresse">[ Caresse ]</a>
                                </div>

                                <form action="index.php" method="get" class="mt-2">
                                    <input type="hidden" name="action" value="nourrirAnimal">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <select name="provision_id" required style="border: 2px inset #888; font-family: 'Comic Sans MS', cursive; max-width: 160px;">
                                        <?php foreach($this->provisions as $pid => $provision): ?>
                                            <option value="<?= $pid ?>">
                                                <?= htmlspecialchars(basename(str_replace('\\', '/', $provision->getType()))) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" style="font-weight: bold; background: #ffcc44; border: 2px outset; font-family: 'Comic Sans MS', cursive;">Nourrir</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
            <h2>Mes Provisions</h2>
            </div>
                <div class="row">
                <?php foreach($this->provisions as $id => $provision): ?>
                    <?php
                        $classe        = get_class($provision);
                        $typeProvision = strtolower(substr($classe, strrpos($classe, '\\') + 1));

                        // Mapping explicite pour les noms qui ne correspondent pas
                        $mapping = [
                            'hamburger' => 'burger',
                        ];
                        $filename = $mapping[$typeProvision] ?? $typeProvision;

                        $extensions = ['png', 'webp', 'gif', 'jpg', 'jpeg'];
                        $imagePath  = null;

                        foreach ($extensions as $ext) {
                            $testPath = __DIR__ . '/asset/' . $filename . '.' . $ext;
                            if (file_exists($testPath)) {
                                $imagePath = 'asset/' . $filename . '.' . $ext;
                                break;
                            }
                        }
                    ?>
                    <div class="col">
                        <div class="card card-90s" style="background: #fff8e7 !important; border-color: #cc8800 !important;">
                            <div class="card-body">
                                <?php if ($imagePath): ?>
                                    <img src="<?= $imagePath ?>" alt="<?= $typeProvision ?>" style="display:block; margin:auto; max-width:80px; max-height:80px; image-rendering: pixelated;">
                                <?php endif; ?>
                                <h5 style="text-align: center;"><?= htmlspecialchars(ucfirst($typeProvision)) ?></h5>
                                <?php $valeurs = $provision->getValeur(); ?>
                                <ul style="list-style: none; padding-left: 0; font-size: 0.85em;">
                                    <li><span class="stat-soif">Soif :</span> <?= $valeurs['soif'] ?></li>
                                    <li><span class="stat-faim">Faim :</span> <?= $valeurs['faim'] ?></li>
                                    <li><span class="stat-sante">Santé :</span> <?= $valeurs['sante'] ?></li>
                                    <li><span class="stat-humeur">Humeur :</span> <?= $valeurs['humeur'] ?></li>
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
