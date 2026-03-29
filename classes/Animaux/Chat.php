<?php

namespace Animaux;

class Chat extends Animal
{
    public function __construct($nom)
    {
        parent::__construct($nom);
        $this->faim   = 30;
        $this->soif   = 40;
        $this->humeur = 80;
    }

    public function getSymbol(): string
    {
        return '=^.^=';
    }

    // Agile : 30 % de chance d'esquiver chaque attaque reçue
    public function esquive(): bool
    {
        return rand(1, 100) <= 30;
    }

    // Légèrement plus défensif que la moyenne
    public function getForceDefense(): int
    {
        return rand(5, 12) + (int)($this->sante / 25);
    }
}
