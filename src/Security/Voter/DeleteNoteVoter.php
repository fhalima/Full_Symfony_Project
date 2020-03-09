<?php

namespace App\Security\Voter;

use App\Entity\Note;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class DeleteNoteVoter extends Voter
{
    /**
     * @var Security
     */
    private $security;

    /**
     * DeleteNoteVoter constructor.  poir récupérer le service par autowiring
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Savoir si le voter doit intervenir
     * @param string $attribute l'attribut correspond au nom du droit (comme un role)
     * @param string $subject sur quoi cherche t-on a vérifier les droits
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html

        //faire intervenir le Voter seulement quand on verifie le droit NOTE_DELETE
        //sur une Note
        ////Exemple : isGranted("NOTE_DELETE", $note)
        return $attribute === 'NOTE_DELETE'
            && $subject instanceof Note;

        //operateur Instancof : verifier qu'un objet appartient à une classe
        //$objet instanceof Classe
    }

    /**
     * @param string $attribute l'attribut (ici 'POST_DELETE')
     * @param mixed $subject   le sujet du droit d'accès (ici l'instanece de Note)
     * @param TokenInterface $token un jeton pour récupérer l'utilisateur actuel
     * @return bool     Est ce que l'utilisateur obtient le droit
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        //récupération de l'utilisateur grace au jeton
        /**
         * @var UserInterface $user
         */
        $user = $token->getUser();
        // Si l'utilisateur actuel n'est pas connecté il n'a pas le droit d'accès
        if (!$user instanceof UserInterface) {
            return false;
        }

        //Si l'utilisateur est administrateur : accès accordé

        if($this->security->isGranted('ROLE_ADMIN')){
            return true;
        }


        //Si l'utilisateur est l'auteur de la  note : accès accordé
        if($subject->getUser() === $user){
            return true;
        }




        //Supprimer le swich case qui été ecrit par défaut


//        // ... (check conditions and return true to grant permission) ...
//        switch ($attribute) {
//            case 'POST_EDIT':
//                // logic to determine if the user can EDIT
//                // return true or false
//                break;
//            case 'POST_VIEW':
//                // logic to determine if the user can VIEW
//                // return true or false
//                break;
//        }

        return false;
    }
}
