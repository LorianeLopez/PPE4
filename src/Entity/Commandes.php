<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandes
 *
 * @ORM\Table(name="commandes", indexes={@ORM\Index(name="numeroUtilisateur", columns={"numeroUtilisateur"}), @ORM\Index(name="statut", columns={"statut"}), @ORM\Index(name="idCommande", columns={"idCommande"})})
 * @ORM\Entity
 */
class Commandes
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCommande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcommande;

    /**
     * @var int
     *
     * @ORM\Column(name="prixTotal", type="integer", nullable=false)
     */
    private $prixtotal;

    /**
     * @var \Statut
     *
     * @ORM\ManyToOne(targetEntity="Statut")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="statut", referencedColumnName="idStatut")
     * })
     */
    private $statut;

    /**
     * @var \Utilisateurs
     *
     * @ORM\ManyToOne(targetEntity="Utilisateurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="numeroUtilisateur", referencedColumnName="numero")
     * })
     */
    private $numeroutilisateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Livres", inversedBy="idcommande")
     * @ORM\JoinTable(name="contenu_commandes",
     *   joinColumns={
     *     @ORM\JoinColumn(name="idCommande", referencedColumnName="idCommande")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="idLivre", referencedColumnName="id_livre")
     *   }
     * )
     */
    private $idlivre;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idlivre = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    function getIdcommande() {
        return $this->idcommande;
    }

    function getPrixtotal() {
        return $this->prixtotal;
    }

    function getStatut(): \Statut {
        return $this->statut;
    }

    function getNumeroutilisateur(): \Utilisateurs {
        return $this->numeroutilisateur;
    }

    function getIdlivre(): \Doctrine\Common\Collections\Collection {
        return $this->idlivre;
    }

    function setIdcommande($idcommande) {
        $this->idcommande = $idcommande;
    }

    function setPrixtotal($prixtotal) {
        $this->prixtotal = $prixtotal;
    }

    function setStatut(\Statut $statut) {
        $this->statut = $statut;
    }

    function setNumeroutilisateur(\Utilisateurs $numeroutilisateur) {
        $this->numeroutilisateur = $numeroutilisateur;
    }

    function setIdlivre(\Doctrine\Common\Collections\Collection $idlivre) {
        $this->idlivre = $idlivre;
    }



}
