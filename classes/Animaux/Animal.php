<?php

namespace Animaux;

use Jeu;

class Animal
{
    protected $nom;
    protected $age;
    protected $faim;
    protected $soif;
    protected $humeur;
    protected $sante;
    protected $vivant;

    public function __construct($nom)
    {
        $this->faim = 50;
        $this->soif = 50;
        $this->humeur = 100;
        $this->sante = 100;
        $this->nom = $nom;
        $this->age = 0;
        $this->vivant = true;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function estVivant()
    {
        return $this->vivant;
    }

    public function getSante()
    {
        return $this->sante;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getHumeur()
    {
        return $this->humeur;
    }

    public function getFaim()
    {
        return $this->faim;
    }

    public function getSoif()
    {
        return $this->soif;
    }

    public function dormir()
    {
        if ($this->vivant) {
            
            $this->vieillir();
            $this->evoluerFaim(rand(5, 15));
            $this->evoluerSoif(rand(5, 15));
            $this->evoluerHumeur(rand(-80, 80));

            if ($this->faim == 100) {
                $this->evoluerSante(rand(-30, -10));
            }

            if ($this->soif == 100) {
                $this->evoluerSante(rand(-30, -10));
            }

            if ($this->humeur == 0) {
                $this->evoluerSante(rand(-20, 0));
            }
        }
    }

    public function nourrir($provision)
    {
        if ($this->vivant) {
            $this->evoluerFaim($provision->getImpactFaim());
            $this->evoluerSoif($provision->getImpactSoif());
            $this->evoluerSante($provision->getImpactSante());
            $this->evoluerHumeur($provision->getImpactHumeur());
        }
    }

    public function soigner()
    {
        $this -> evoluerSante(rand(20,100));
    }

    public function carresse()
    {
        $this -> evoluerHumeur(rand(20,100));
    }

    protected function vieillir()
    {
        $this->age++;

        if ($this->age >= 10) {

            $aleatoire = rand(0, 100);

            if ($aleatoire <= 20) {
                $jeu = Jeu::getInstance();
                $enfant = new Animal($this->nom . ' Jr.');
                $jeu->ajouteAnimal($enfant);
            }
        }
    }

    protected function evoluerFaim($variation)
    {
        $this->faim += $variation;
        $this->faim = min($this->faim, 100);
        $this->faim = max($this->faim, 0);
    }

    protected function evoluerSoif($variation)
    {
        $this->soif += $variation;
        $this->soif = min($this->soif, 100);
        $this->soif = max($this->soif, 0);
    }

    protected function evoluerHumeur($variation)
    {
        $this->humeur += $variation;
        $this->humeur = min($this->humeur, 100);
        $this->humeur = max($this->humeur, 0);
    }

    protected function evoluerSante($variation)
    {
        $this->sante += $variation;
        $this->sante = min($this->sante, 100);
        $this->sante = max($this->sante, 0);

        if ($this->sante == 0) {
            $this->vivant = false;
        }
    }

}