<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandes
 *
 * @ORM\Table(name="contenu_Commandes", indexes={@ORM\Index(name="idCommande", columns={"idCommande"}), @ORM\Index(name="idLivre", columns={"idLivre"})})
 * @ORM\Entity
 */
class ContenuCommandes
{
    
    /**
     * @var int
     *
     * @ORM\Column(name="idContenu", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcontenu;
    
    /**
     * @var \Commandes
     *
     * @ORM\ManyToOne(targetEntity="Commandes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCommande", referencedColumnName="idCommande")
     * })
     */
    private $idcommande;

    /**
     * @var \Livres
     *
     * @ORM\ManyToOne(targetEntity="Livres")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idLivre", referencedColumnName="id_livre")
     * })
     */
    private $idlivre;

    /**
     * @var int
     *
     * @ORM\Column(name="nbLivre", type="integer", nullable=false)
     */
    private $nbLivre;

    function getIdcommande() {
        return $this->idcommande;
    }

    function getIdlivre(){
        return $this->idlivre;
    }

    function getNbLivre() {
        return $this->nbLivre;
    }

    function setIdcommande($idcommande) {
        $this->idcommande = $idcommande;
    }

    function setIdlivre($idlivre) {
        $this->idlivre = $idlivre;
    }

    function setNbLivre($nbLivre) {
        $this->nbLivre = $nbLivre;
    }
    
    function getIdcontenu() {
        return $this->idcontenu;
    }

    function setIdcontenu($idcontenu) {
        $this->idcontenu = $idcontenu;
    }






}
