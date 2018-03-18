<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livres
 *
 * @ORM\Table(name="livres", indexes={@ORM\Index(name="saga_livres", columns={"saga_livres"})})
 * @ORM\Entity
 */
class Livres
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_livre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLivre;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_livre", type="string", length=255, nullable=false)
     */
    private $titreLivre;

    /**
     * @var string
     *
     * @ORM\Column(name="couverture_livre", type="text", length=65535, nullable=false)
     */
    private $couvertureLivre;

    /**
     * @var string
     *
     * @ORM\Column(name="resume_livre", type="text", length=65535, nullable=false)
     */
    private $resumeLivre;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_livre", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixLivre;

    /**
     * @var int
     *
     * @ORM\Column(name="stock_livre", type="integer", nullable=false)
     */
    private $stockLivre;

    /**
     * @var \Sagas
     *
     * @ORM\ManyToOne(targetEntity="Sagas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="saga_livres", referencedColumnName="titre_saga")
     * })
     */
    private $sagaLivres;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idcommande = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    function getIdLivre() {
        return $this->idLivre;
    }

    function getTitreLivre() {
        return $this->titreLivre;
    }

    function getCouvertureLivre() {
        return $this->couvertureLivre;
    }

    function getResumeLivre() {
        return $this->resumeLivre;
    }

    function getPrixLivre() {
        return $this->prixLivre;
    }

    function getStockLivre() {
        return $this->stockLivre;
    }

    function getSagaLivres(){
        return $this->sagaLivres;
    }

    function setIdLivre($idLivre) {
        $this->idLivre = $idLivre;
    }

    function setTitreLivre($titreLivre) {
        $this->titreLivre = $titreLivre;
    }

    function setCouvertureLivre($couvertureLivre) {
        $this->couvertureLivre = $couvertureLivre;
    }

    function setResumeLivre($resumeLivre) {
        $this->resumeLivre = $resumeLivre;
    }

    function setPrixLivre($prixLivre) {
        $this->prixLivre = $prixLivre;
    }

    function setStockLivre($stockLivre) {
        $this->stockLivre = $stockLivre;
    }

    function setSagaLivres($sagaLivres) {
        $this->sagaLivres = $sagaLivres;
    }

    function __toString() {
        return $this->idLivre;
    }


}
