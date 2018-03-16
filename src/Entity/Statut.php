<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statut
 *
 * @ORM\Table(name="statut", indexes={@ORM\Index(name="idStatut", columns={"idStatut"})})
 * @ORM\Entity
 */
class Statut
{
    /**
     * @var int
     *
     * @ORM\Column(name="idStatut", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idstatut;

    /**
     * @var string
     *
     * @ORM\Column(name="libelleStatut", type="string", length=255, nullable=false)
     */
    private $libellestatut;

    function getIdstatut() {
        return $this->idstatut;
    }

    function getLibellestatut() {
        return $this->libellestatut;
    }

    function setIdstatut($idstatut) {
        $this->idstatut = $idstatut;
    }

    function setLibellestatut($libellestatut) {
        $this->libellestatut = $libellestatut;
    }



}
