<?php

use Animaux\Animal;
use Animaux\Chat;
use Animaux\Chien;
use Animaux\Lapin;
use Provisions\Coca;
use Provisions\Eau;
use Provisions\Hamburger;
use Provisions\Pasteque;

class Jeu
{
    private static Jeu|null $instance = null;
    private array $animaux;
    private array $provisions;
    private int $points;
    private array $messages;

    /**
     * Initialise la session et recharge l'etat du jeu.
     */
    private function __construct()
    {
        session_start();

        $this->animaux = $_SESSION['animaux'] ?? [];
        $this->provisions = $_SESSION['provisions'] ?? [];
        $this->points = $_SESSION['points'] ?? 3;
        $this->messages = $_SESSION['messages'] ?? [];
    }

    public static function getInstance(): Jeu
    {
        if (Jeu::$instance === null) {
            Jeu::$instance = new Jeu();
        }

        return Jeu::$instance;
    }

    public function executeAction()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'creerAnimal':
                    $this->actionCreerAnimal();

                    //$this->messages[] = "Vous avez crée un nouvel animal !";
                    break;

                case 'rechercherProvisions':
                    $this->actionRechercherProvisions();
                    //les messages sont dans action pour print la valeur de l'animal et/ou la provision
                    //$this->messages[] = "Vous avez trouve de nouvelles provisions !";
                    break;

                case 'armageddon':
                    $this->actionArmageddon();
                    $this->messages[] = "L'armageddon a eu lieu ! Tous les animaux et provisions ont été détruits !";
                    break;

                case 'nuit':
                    $this->actionNuit();
                    $this->messages[] = "La nuit est tombée !";
                    break;

                case 'soignerAnimal':
                    $this->actionSoignerAnimal();
                    //$this->messages[] = "Vous avez soigner un animal !";
                    break;

                case 'carresse':
                    $this->actionCarresse();
                    //$this->messages[] = "Vous avez caressé un animal !";
                    break;

                case 'nourrirAnimal':
                    $this->actionNourrir();
                    //$this->messages[] = "Vous avez nourri un animal !";
                    break;

                case 'jouerEnsemble':
                    $this->actionJouerEnsemble();
                    break;

                case 'combat':
                    $this->actionCombat();
                    break;
            }

            $this->sauvegarde();
            $this->recharge();
        }
    }

    public function afficheInterface()
    {
        require 'interface.php';
    }

    public function ajouteAnimal(Animal $animal)
    {
        $this->animaux[] = $animal;
    }

    private function actionSoignerAnimal()
    {
        if ($this->points >= 3 && isset($_GET['id'], $this->animaux[$_GET['id']])) {
            $id = $_GET['id'];
            $animal = $this->animaux[$id];

            if ($animal->estVivant() == true && $animal->getSante() < 100) {
                $this->points -= 3;
                $animal->soigner();
                $this->messages[] = "Vous avez soigné " . $animal->getNom() . "!";
            }
        }
    }

    private function actionCarresse()
    {
        if ($this->points >= 2 && isset($_GET['id'], $this->animaux[$_GET['id']])) {
            $id = $_GET['id'];
            $animal = $this->animaux[$id];

            if ($animal->estVivant() == true) {
                $this->points -= 2;
                $animal->carresse();
                $this->messages[] = "Vous avez caressé " . $animal->getNom() . "!";
            }
        }
    }

    private function actionNourrir()
    {
        if (isset($_GET['id']) && isset($_GET['provision_id'])) {
            $animalId = $_GET['id'];
            $provisionId = $_GET['provision_id'];

            if ($this->points >= 1 && isset($this->animaux[$animalId]) && isset($this->provisions[$provisionId])) {
                $animal = $this->animaux[$animalId];
                $provision = $this->provisions[$provisionId];

                $this->points -= 1;
                $animal->nourrir($provision);
                $this->messages[] = "Vous avez nourri " . $animal->getNom() . " avec une " . $provision->getType() . "!";
                unset($this->provisions[$provisionId]);
            }
        }
    }

    private function sauvegarde()
    {
        $_SESSION['animaux'] = $this->animaux;
        $_SESSION['provisions'] = $this->provisions;
        $_SESSION['points'] = $this->points;
        $_SESSION['messages'] = $this->messages;
    }

    private function recharge()
    {
        header('Location: index.php');
        exit();
    }

    private function actionNuit()
    {
        if ($this->points == 0) {
            foreach ($this->animaux as $animal) {
                $animal->dormir();
            }

            $this->points = 3;
        }
    }

    private function actionRechercherProvisions()
    {
        if ($this->points >= 1) {
            $this->points--;

            $aleatoire = rand(0, 100);

            if ($aleatoire <= 30) {
                $this->provisions[] = new Pasteque();
                $this->messages[] = "Vous avez trouvé une pasteque !";
            } elseif ($aleatoire <= 50) {
                $this->provisions[] = new Hamburger();
                $this->messages[] = "Vous avez trouvé un hamburger !";
            } elseif ($aleatoire <= 80) {
                $this->provisions[] = new Coca();
                $this->messages[] = "Vous avez trouvé un coca !";
            } elseif ($aleatoire <= 90) {
                $this->provisions[] = new Eau();
                $this->messages[] = "Vous avez trouvé une eau !";
            }
        }
    }

    private function actionCreerAnimal()
    {
        if ($this->points >= 1 && isset($_GET['nom'])) {
            $this->points--;

            $nom  = $_GET['nom'];
            $type = $_GET['type'] ?? 'Animal';

            $animal = match($type) {
                'Chat'  => new Chat($nom),
                'Chien' => new Chien($nom),
                'Lapin' => new Lapin($nom),
                default => new Animal($nom),
            };

            $this->ajouteAnimal($animal);
            $this->messages[] = "Vous avez créé " . htmlspecialchars($nom) . " le " . htmlspecialchars($type) . " !";
        }
    }

    private function actionJouerEnsemble()
    {
        if ($this->points >= 1 && isset($_GET['id1'], $_GET['id2'])) {
            $id1 = $_GET['id1'];
            $id2 = $_GET['id2'];

            if ($id1 !== $id2
                && isset($this->animaux[$id1], $this->animaux[$id2])
                && $this->animaux[$id1]->estVivant()
                && $this->animaux[$id2]->estVivant()
            ) {
                $a1 = $this->animaux[$id1];
                $a2 = $this->animaux[$id2];

                $this->points--;
                $blessure = $a1->jouerAvec($a2);

                $this->messages[] = $a1->getNom() . ' et ' . $a2->getNom() . ' ont joué ensemble ! (+humeur)' . $blessure;
            }
        }
    }

    private function actionCombat()
    {
        if ($this->points >= 2 && isset($_GET['id1'], $_GET['id2'])) {
            $id1 = $_GET['id1'];
            $id2 = $_GET['id2'];

            if ($id1 !== $id2
                && isset($this->animaux[$id1], $this->animaux[$id2])
                && $this->animaux[$id1]->estVivant()
                && $this->animaux[$id2]->estVivant()
            ) {
                $attaquant = $this->animaux[$id1];
                $cible     = $this->animaux[$id2];

                $this->points -= 2;

                // 70% chance de tuer, sinon dégâts aléatoires
                if (rand(1, 100) <= 70) {
                    $degats = $cible->getSante();
                    $this->messages[] = $attaquant->getNom() . ' attaque ' . $cible->getNom() . ' — coup fatal ! (-' . $degats . ' PV)';
                } else {
                    $degats = rand(10, 50);
                    $this->messages[] = $attaquant->getNom() . ' attaque ' . $cible->getNom() . ' — -' . $degats . ' PV !';
                }

                $cible->evoluerSante(-$degats);
            }
        }
    }

    private function actionArmageddon()
    {
        $this->animaux = [];
        $this->provisions = [];
        $this->points = 3;
        $this->messages = [];
    }
}
