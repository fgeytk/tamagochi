<?php

namespace Provisions;

abstract class Provision
    
{
    protected $impactSoif = 0;
    protected $impactFaim = 0;
    protected $impactSante = 0;
    protected $impactHumeur = 0;
    
    
        //le get class fournit le nom aussi 
        public function getType() {
            return get_class($this);
        }

        // deprecié
        public function getValeur() {
            return [
                'soif' => $this->impactSoif,
                'faim' => $this->impactFaim,
                'sante' => $this->impactSante,
                'humeur' => $this->impactHumeur
            ];
        }
        
        public function getImpactSoif() {
            return $this->impactSoif;
        }
        public function getImpactFaim() {
            return $this->impactFaim;
        }
        public function getImpactSante() {
            return $this->impactSante;
        }
        public function getImpactHumeur() {
            return $this->impactHumeur;
        }
    
}


