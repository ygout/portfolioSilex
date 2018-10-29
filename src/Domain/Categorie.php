<?php

namespace Portfolio\Domain;

class Categorie 
{
    /**
     * Categorie id.
     *
     * @var integer
     */
    private $id;

    /**
     * Categorie libelle.
     *
     * @var string
     */
    private $libelle;

   

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getLibelle() {
        return $this->libelle;
    }

    public function setLibelle($libelle) {
        $this->libelle = $libelle;
    }
    
    public function __toString(){
        return $this->getLibelle();
    }

    
}