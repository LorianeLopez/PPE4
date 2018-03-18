<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sagas
 *
 * @ORM\Table(name="sagas", indexes={@ORM\Index(name="titre_saga", columns={"titre_saga"})})
 * @ORM\Entity
 */
class Sagas
{
    /**
     * @var string
     *
     * @ORM\Column(name="titre_saga", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $titreSaga;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur_saga", type="string", length=255, nullable=false)
     */
    private $auteurSaga;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_livres_saga", type="integer", nullable=false)
     */
    private $nbLivresSaga;

    function getTitreSaga() {
        return $this->titreSaga;
    }

    function getAuteurSaga() {
        return $this->auteurSaga;
    }

    function getNbLivresSaga() {
        return $this->nbLivresSaga;
    }

    function setTitreSaga($titreSaga) {
        $this->titreSaga = $titreSaga;
    }

    function setAuteurSaga($auteurSaga) {
        $this->auteurSaga = $auteurSaga;
    }

    function setNbLivresSaga($nbLivresSaga) {
        $this->nbLivresSaga = $nbLivresSaga;
    }

    public function __toString(){
        return $this->titreSaga;
    }

}
