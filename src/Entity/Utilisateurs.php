<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**

 * Utilisateurs

 *

 * @ORM\Table(name="utilisateurs")

 * @ORM\Entity

 */
class Utilisateurs implements \Symfony\Component\Security\Core\User\UserInterface, \Serializable {

    /**

     * @var string

     *

     * @ORM\Column(name="numero", type="string", length=255, nullable=false)

     * @ORM\Id

     */
    private $numero;

    /**

     * @var string

     *

     * @ORM\Column(name="nom", type="string", length=255, nullable=false)

     */
    private $nom;

    /**

     * @var string

     *

     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)

     */
    private $prenom;

    /**

     * @var string

     *

     * @ORM\Column(name="codePerso", type="string", length=255, nullable=false)

     */
    private $codeperso;

    /**

     * @ORM\Column(type="boolean")

     */
    private $isActive;

    /**

     * @ORM\Column(type="string", length = 255)

     */
    private $role;

    function __construct() {
        
    }

    function getIsActive() {

        return $this->isActive;
    }

    function getRole() {

        return $this->role;
    }

    function setIsActive($isActive) {

        $this->isActive = $isActive;
    }

    function setRole($role) {

        $this->role = $role;
    }

    function getNumero() {

        return $this->numero;
    }

    function getNom() {

        return $this->nom;
    }

    function getPrenom() {

        return $this->prenom;
    }

    function getCodeperso() {

        return $this->codeperso;
    }

    function setNumero($numero) {

        $this->numero = $numero;
    }

    function setNom($nom) {

        $this->nom = $nom;
    }

    function setPrenom($prenom) {

        $this->prenom = $prenom;
    }

    function setCodeperso($codeperso) {

        $this->codeperso = password_hash($codeperso, PASSWORD_BCRYPT, array("cost" => 13));
    }

    function __toString() {

        return $this->numero;
    }

    public function getUsername() {

        return $this->numero;
    }

    public function getPassword() {

        return $this->codeperso;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {

        return array($this->role);
    }

    public function getSalt() {

        return null;
    }

    public function serialize() {

        return serialize(array(
            $this->numero,
            $this->nom,
            $this->codeperso
        ));
    }

    public function unserialize($serialized) {

        list (

                $this->numero,
                $this->nom,
                $this->codeperso

                ) = unserialize($serialized);
    }

    public function alea() {

        $data = range(0, 9) + array_fill_keys(range(10, 24), '');

        shuffle($data);

        echo '<pre><table>';

        foreach (array_chunk($data, 5, true) as $r) {

            echo '<tr>', '<td>', implode('</td><td>', $r), '</td></tr>';
        }

        echo '</table>';
    }

}
