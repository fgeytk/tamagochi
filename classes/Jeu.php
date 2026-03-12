<?php

use Animaux\Animal;
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
    private array $message;
    $message += "";

    /**
    * - initialise la session
    * - récupère (si c'est présent) la liste des animaux et des provisions
    */
    private function __construct()
    {
        session_start();

        // ici, j'ai un tableau $_SESSION qui contient peut etre des animaux
        $this->animaux = $_SESSION['animaux'] ?? [];
        $this->provisions = $_SESSION['provisions'] ?? [];
        $this->points = $_SESSION['points'] ?? 3;
    }

    public static function getInstance(): Jeu
    {
        if (Jeu::$instance === null) {
            Jeu::$instance = new Jeu();
        }

        return Jeu::$instance;
    }

    // executer action
    public function executeAction() 
    {
        if (isset($_GET['action'])) {

            $action = $_GET['action'];
            
            switch ($action) {

                case 'creerAnimal':
                    $this->actionCreerAnimal();
                    $this ->message = "Vous avez créé un nouvel animal !";
                    break;

                case 'rechercherProvisions':
                    $this->actionRechercherProvisions();
                    $this ->message = "Vous avez trouvé de nouvelles provisions !";
                    break;

                case 'armageddon':
                    $this->actionArmageddon();
                    $this ->message = "L'armageddon a eu lieu ! Tous les animaux et provisions ont été détruits !";
                    break;

                case 'nuit':
                    $this->actionNuit();
                    $this ->message = "La nuit est tombée !";
                    break;
                case 'soignerAnimal':
                    $this->actionsoignerAnimal();
                    $this ->message = "Vous avez soigné un animal !";
                    break;
                case 'carresse':
                    $this->actionCarresse();
                    $this ->message = "Vous avez caressé un animal !";
                    break;
                case 'nourrirAnimal':
                    $this->actionNourrir();
                    $this ->message = "Vous avez nourri un animal !";
                    break;
            }

            $this->sauvegarde();
            $this->recharge();
        }
    }

    // afficher interface
    public function afficheInterface() 
    {
        require 'interface.php';
    }

    public function ajouteAnimal(Animal $animal)
    {
        $this->animaux[] = $animal;
    }

    private function actionsoignerAnimal()
    {
        if ($this->points >= 3) {
            
            
            $id = $_GET['id'];

            $animal = $this -> animaux[$id];

            if ($animal -> estVivant() == true & $animal -> getSante() < 100)
            {
                $this->points -= 3;
                $animal ->soigner();
            }
            
        }

    }

    private function actionCarresse()
    {
        if ($this->points >= 2) {
            
            
            $id = $_GET['id'];

            $animal = $this -> animaux[$id];

            if ($animal -> estVivant() == true)
            {
                $this->points -= 2;
                $animal ->carresse();
            }
            
        }

    }

    private function actionNourrir()
    {
        // Récupère l'id de l'animal et de la provision
        if (isset($_GET['id']) && isset($_GET['provision_id'])) {
            $animalId = $_GET['id'];
            $provisionId = $_GET['provision_id'];
            // Vérifie que les ids existent
            if (isset($this->animaux[$animalId]) && isset($this->provisions[$provisionId])) {
                $animal = $this->animaux[$animalId];
                $provision = $this->provisions[$provisionId];
                
                $this -> points -= 1;

                // Applique les impacts de la provision à l'animal avec la method public
                $animal->nourrir($provision);
                // Retire la provision de l'inventaire
                unset($this->provisions[$provisionId]);
            }
        }
    }

    private function sauvegarde()
    
    {
        $_SESSION['animaux'] = $this->animaux;
        $_SESSION['provisions'] = $this->provisions;
        $_SESSION['points'] = $this->points;
    }

    private function recharge()
    {
        header('Location: index.php');
        exit();
    }

    // nourrir, caresser, ...

    private function actionNuit()
    {
        if ($this->points == 0) {

            // Faire dormir chaque animal

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
                $this->provisions[] = new Pasteque;
            } elseif ($aleatoire <= 50) {
                $this->provisions[] = new Hamburger;
            } elseif ($aleatoire <= 80) {
                $this->provisions[] = new Coca;
            } elseif ($aleatoire <= 90) {
                $this->provisions[] = new Eau;
            }
        }
    }

    private function actionCreerAnimal()
    {
        if ($this->points >= 1) {

            $this->points--;

            $nom = $_GET['nom'];
            $animal = new Animal($nom);
            $this->ajouteAnimal($animal);
        }
    }

    private function actionArmageddon()
    {
        $this->animaux = [];
        $this->provisions = [];
        $this->points = 3;
    }


}