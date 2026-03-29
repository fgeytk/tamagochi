<?php

namespace Animaux;

class Chien extends Animal
{
    public function __construct($nom)
    {
        parent::__construct($nom);
        $this->faim   = 70;
        $this->soif   = 60;
        $this->humeur = 100;
    }

    public function getSymbol(): string
    {
        return 'U^.^U';
    }

    // Fort : attaque et défense supérieures à la moyenne
    public function getForceAttaque(): int
    {
        return rand(13, 22) + (int)($this->humeur / 12);
    }

    public function getForceDefense(): int
    {
        return rand(6, 12) + (int)($this->sante / 25);
    }
}
