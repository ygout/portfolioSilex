<?php

namespace Portfolio\Domain;

class Projet {

    /**
     * Projet id.
     *
     * @var integer
     */
    private $id;

    /**
     * Projet titre.
     *
     * @var string
     */
    private $titre;

    /**
     * Associated categorie.
     *
     * @var \Portfolio\Domain\Categorie
     */
    private $categorie;

    /**
     * Projet image.
     *
     * @var string
     */
    private $image;

    /**
     * Projet description.
     *
     * @var string
     */
    private $description;

    /**
     * Projet bilan.
     *
     * @var string
     */
    private $bilan;

    /**
     * Projet outils.
     *
     * @var string
     */
    private $outils;

    /**
     * Projet langages.
     *
     * @var string
     */
    private $langages;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function getCategorie() {
        return $this->categorie;
    }

    public function setCategorie($categorie) {
        $this->categorie = $categorie;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getBilan() {
        return $this->bilan;
    }

    public function setBilan($bilan) {
        $this->bilan = $bilan;
    }

    public function getOutils() {
        return $this->outils;
    }

    public function setOutils($outils) {
        $this->outils = $outils;
    }

    public function getLangages() {
        return $this->langages;
    }

    public function setLangages($langages) {
        $this->langages = $langages;
    }

}