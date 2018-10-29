<?php

namespace Portfolio\DAO;


use Portfolio\Domain\Categorie;

class CategorieDAO extends DAO
{
     /**
     * Returns a category matching the supplied id.
     *
     * @param integer $id
     *
     * @return \Portfolio\Domain\Categorie|throws an exception if no matching category is found
     */
    public function find($id) {
        $sql = "select * from t_categorie where cat_id= ?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No category matching id".$id);

    }



    /**
     * Return a list of all categories.
     *
     * @return array A list of all categories.
     */
    public function findAll() {
        $sql = "select * from t_categorie order by cat_libelle";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $categories = array();
        foreach ($result as $row) {
            $categorieId = $row['cat_id'];
            $categories[$categorieId] = $this->buildDomainObject($row);
        }
        return $categories;
    }
    /**
     * Saves a category into the database.
     *
     * @param \Portfolio\Domain\Categorie $category The category to save
     */
    public function save(Categorie $categorie) {
        $categorieData = array(
            'cat_libelle' => $categorie->getLibelle(),
           
            );

        if ($categorie->getId()) {
            // The category has already been saved : update it
            $this->getDb()->update('t_categorie', $categorieData, array('cat_id' => $categorie->getId()));
        } else {
            // The category has never been saved : insert it
            $this->getDb()->insert('t_categorie', $categorieData);
            // Get the id of the newly created category and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $categorie->setId($id);
        }
    }

    /**
     * Removes a category from the database.
     *
     * @param integer $id The category id.
     */
    public function delete($id) {
        // Delete the category
        $this->getDb()->delete('t_categorie', array('cat_id' => $id));
    }
   

    /**
     * Creates a Category object based on a DB row.
     *
     * @param array $row The DB row containing Category data.
     * @return \Portfolio\Domain\Categorie
     */
      protected function buildDomainObject($row) {
        $categorie = new Categorie();
        $categorie->setId($row['cat_id']);
        $categorie->setLibelle($row['cat_libelle']);
        return $categorie;
    }
}