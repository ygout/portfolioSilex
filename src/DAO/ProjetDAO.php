<?php

namespace Portfolio\DAO;

use Portfolio\Domain\Projet;

class ProjetDAO extends DAO {

    /**
     * @var \Portfolio\DAO\CategorieDAO
     */
    protected $categorieDAO;

    public function setCategorieDAO($categorieDAO) {

        $this->categorieDAO = $categorieDAO;
    }

    /**
     * Return a list of all projects.
     *
     * @return array A list of all projects.
     */
    public function findAll() {
        $sql = "select * from t_projet";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $projets = array();
        foreach ($result as $row) {
            $projetId = $row['projet_id'];


            $projets[$projetId] = $this->buildDomainObject($row);
        }
        return $projets;
    }

    /**
     * Returns a project matching the supplied id.
     *
     * @param integer $id
     *
     * @return \Portfolio\Domain\Projet|throws an exception if no matching project is found
     */
    public function find($id) {
        $sql = "select * from t_projet where projet_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No project matching id " . $id);
    }

    /**
     * Saves a project into the database.
     *
     * @param \Portfolio\Domain\Projet $projet The project to save
     */
    public function save($projet) {
        $projetData = array(
            'projet_titre' => $projet->getTitre(),
            'cat_id' => $projet->getCategorie()->getId(),
            'projet_description' => $projet->getDescription(),
            'projet_bilan' => $projet->getBilan(),
            'projet_outils' => $projet->getOutils(),
            'projet_langages' => $projet->getLangages()
        );

        if ($projet->getId()) {
            // The project has already been saved : update it
            $this->getDb()->update('t_projet', $projetData, array('projet_id' => $projet->getId()));
        } else {
            // The project has never been saved : insert it
            $this->getDb()->insert('t_projet', $projetData);
            // Get the id of the newly created project and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $projet->setId($id);
        }
    }

    /**
     * Removes a project from the database.
     *
     * @param integer $id The project id.
     */
    public function delete($id) {
        // Delete the project
        $this->getDb()->delete('t_projet', array('projet_id' => $id));
    }

    /**
     * Creates a Project object based on a DB row.
     *
     * @param array $row The DB row containing Project data.
     * @return \Portfolio\Domain\Projet
     */
    protected function buildDomainObject($row) {

        //Find the associated category
        $catId = $row['cat_id'];
        $categorie = $this->categorieDAO->find($catId);
        
        $projet=new Projet();
        $projet->setId($row['projet_id']);
        $projet->setTitre($row['projet_titre']);
        $projet->setCategorie($categorie);
        $projet->setImage($row['projet_image']);
        $projet->setDescription($row['projet_description']);
        $projet->setBilan($row['projet_bilan']);
        $projet->setOutils($row['projet_outils']);
        $projet->setLangages($row['projet_langages']);

        return $projet;
    }

}
