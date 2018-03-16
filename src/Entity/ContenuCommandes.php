<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandes
 *
 * @ORM\Table(name="contenu_commandes", indexes={@ORM\Index(name="idCommande", columns={"idCommande"}), @ORM\Index(name="idLivre", columns={"idLivre"})})
 * @ORM\Entity
 */
class ContenuCommandes
{
    /**
     * @var \Commandes
     *
     * @ORM\ManyToOne(targetEntity="Commandes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCommande", referencedColumnName="idCommande")
     * })
     * @ORM\Id
     */
    private $idcommande;

    /**
     * @var \Livres
     *
     * @ORM\ManyToOne(targetEntity="Livres")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idLivre", referencedColumnName="idLivre")
     * })
     * @ORM\Id
     */
    private $idlivre;

    /**
     * @var int
     *
     * @ORM\Column(name="nbLivre", type="int", nullable=false)
     */
    private $nbLivre;

    function getIdcommande(): \Commandes {
        return $this->idcommande;
    }

    function getIdlivre(): \Livres {
        return $this->idlivre;
    }

    function getNbLivre() {
        return $this->nbLivre;
    }

    function setIdcommande(\Commandes $idcommande) {
        $this->idcommande = $idcommande;
    }

    function setIdlivre(\Livres $idlivre) {
        $this->idlivre = $idlivre;
    }

    function setNbLivre($nbLivre) {
        $this->nbLivre = $nbLivre;
    }




}
