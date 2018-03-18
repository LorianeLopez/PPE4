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
    
    
    public $contenu;
    
    public $tailleContenu;

    /**
     * @var float
     *
     * @ORM\Column(name="prixTotal", type="float", nullable=false)
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
     * Constructor
     */
    public function __construct()
    {
        
    }
    
    function getIdcommande() {
        return $this->idcommande;
    }

    function getPrixtotal() {
        return $this->prixtotal;
    }

    function getStatut(){
        return $this->statut;
    }

    function getNumeroutilisateur() {
        return $this->numeroutilisateur;
    }

    function setIdcommande($idcommande) {
        $this->idcommande = $idcommande;
    }

    function setPrixtotal($prixtotal) {
        $this->prixtotal = $prixtotal;
    }

    function setStatut($statut) {
        $this->statut = $statut;
    }

    function setNumeroutilisateur($numeroutilisateur) {
        $this->numeroutilisateur = $numeroutilisateur;
    }

    function getIdcontenu(){
        return $this->idcontenu;
    }


    public function leContenu($lesLivres){
        $this->contenu = $lesLivres;
    }
    
    public function recupContenu(){
        return $this->contenu;
    }
    
    public function laTaille($taille){
        $this->tailleContenu = $taille;
    }
    
    public function recupTaille(){
        return $this->tailleContenu;
    }

    public function __toString() {
        return $this->idcommande;
    }
}
