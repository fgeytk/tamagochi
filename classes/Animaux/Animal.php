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

    public function jouerAvec(Animal $autre): string
    {
        $gain = rand(20, 40);
        $this->evoluerHumeur($gain);
        $autre->evoluerHumeur($gain);

        $message = '';
        if (rand(0, 100) < 30) {
            $blesse = rand(0, 1) ? $this : $autre;
            $blesse->evoluerSante(-rand(5, 15));
            $message = ' Mais ' . $blesse->getNom() . ' s\'est blessé en jouant !';
        }

        return $message;
    }

    // -------- COMBAT --------

    public function getForceAttaque(): int
    {
        return rand(8, 16) + (int)($this->humeur / 12);
    }

    public function getForceDefense(): int
    {
        return rand(3, 8) + (int)($this->sante / 25);
    }

    /** Retourne true si cet animal esquive l'attaque reçue ce tour. */
    public function esquive(): bool
    {
        return false;
    }

    /** Retourne true si cet animal effectue une seconde frappe ce tour. */
    public function doubleAttaque(): bool
    {
        return false;
    }

    /**
     * Simule un combat contre $adversaire, applique les dégâts réels et
     * retourne le log détaillé du combat (tableau de chaînes).
     */
    public function combattre(Animal $adversaire): array
    {
        $log  = [];
        $hpA  = $this->sante;
        $hpB  = $adversaire->sante;

        $log[] = '⚔️  ' . $this->getNom() . ' (' . $hpA . 'pv) VS '
               . $adversaire->getNom() . ' (' . $hpB . 'pv) — QUE LE COMBAT COMMENCE !';

        for ($round = 1; $round <= 8 && $hpA > 0 && $hpB > 0; $round++) {
            $msg = 'Tour ' . $round . ' : ';

            // A attaque B
            if ($adversaire->esquive()) {
                $msg .= $adversaire->getNom() . ' esquive ! ';
            } else {
                $dmg  = max(1, $this->getForceAttaque() - $adversaire->getForceDefense());
                $hpB  = max(0, $hpB - $dmg);
                $msg .= $this->getNom() . ' frappe ' . $adversaire->getNom() . ' (−' . $dmg . 'pv). ';
            }

            // Double frappe de A
            if ($hpB > 0 && $this->doubleAttaque()) {
                if ($adversaire->esquive()) {
                    $msg .= 'Frappe rapide esquivée ! ';
                } else {
                    $dmg2 = max(1, $this->getForceAttaque() - $adversaire->getForceDefense());
                    $hpB  = max(0, $hpB - $dmg2);
                    $msg .= '⚡ Frappe rapide ! −' . $dmg2 . 'pv. ';
                }
            }

            // B riposte (si encore debout)
            if ($hpB > 0) {
                if ($this->esquive()) {
                    $msg .= $this->getNom() . ' esquive la riposte ! ';
                } else {
                    $dmg  = max(1, $adversaire->getForceAttaque() - $this->getForceDefense());
                    $hpA  = max(0, $hpA - $dmg);
                    $msg .= $adversaire->getNom() . ' riposte (−' . $dmg . 'pv). ';
                }

                // Double frappe de B
                if ($hpA > 0 && $adversaire->doubleAttaque()) {
                    if ($this->esquive()) {
                        $msg .= 'Frappe rapide esquivée !';
                    } else {
                        $dmg2 = max(1, $adversaire->getForceAttaque() - $this->getForceDefense());
                        $hpA  = max(0, $hpA - $dmg2);
                        $msg .= '⚡ Frappe rapide ! −' . $dmg2 . 'pv.';
                    }
                }
            }

            $msg .= ' [' . $this->getNom() . ': ' . $hpA . 'pv | ' . $adversaire->getNom() . ': ' . $hpB . 'pv]';
            $log[] = $msg;
        }

        // Résultat
        if ($hpA > $hpB) {
            $log[] = '🏆 ' . $this->getNom() . ' remporte le combat !';
        } elseif ($hpB > $hpA) {
            $log[] = '🏆 ' . $adversaire->getNom() . ' remporte le combat !';
        } else {
            $log[] = '🤝 Egalité épique ! Les deux guerriers s\'effondrent épuisés !';
        }

        // Application des dégâts réels
        $this->evoluerSante(-max(0, $this->sante - $hpA));
        $adversaire->evoluerSante(-max(0, $adversaire->sante - $hpB));

        return $log;
    }

    public function getSymbol(): string
    {
        return '(o_o)';
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

    public function evoluerSante($variation)
    {
        $this->sante += $variation;
        $this->sante = min($this->sante, 100);
        $this->sante = max($this->sante, 0);

        if ($this->sante == 0) {
            $this->vivant = false;
        }
    }

}