<?php

namespace Animaux;

class Lapin extends Animal
{
    public function __construct($nom)
    {
        parent::__construct($nom);
        $this->faim   = 50;
        $this->soif   = 30;
        $this->humeur = 60;
        $this->sante  = 80;
    }

    public function getSymbol(): string
    {
        return "(='.'=)";
    }

    // Rapide : 40 % de chance de frapper deux fois par tour
    public function doubleAttaque(): bool
    {
        return rand(1, 100) <= 40;
    }

    // Chaque coup est plus faible mais le lapin compense avec la vitesse
    public function getForceAttaque(): int
    {
        return rand(5, 11) + (int)($this->humeur / 12);
    }
}
