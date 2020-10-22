<?php 

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

use Doctrine\ORM\EntityRepository;

/**
* class ProgramType
*
* @author David Alméciga <walmeciga@minambiente.gov.co>
*/
class ProgramType extends AbstractType
{

  private $tokenStorage;

  private $authorizationChecker;

  public function __construct(TokenStorageInterface $tokenStorage, AuthorizationChecker $authorizationChecker)
  {
      $this->tokenStorage = $tokenStorage;
      $this->authorizationChecker = $authorizationChecker;
  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
      $user = $this->tokenStorage->getToken()->getUser();

      $isAdmin = $this->authorizationChecker->isGranted('ROLE_ADMIN');

      $queryBuilder = function (EntityRepository $er) use ($user, $isAdmin) {

        $qb = $er->createQueryBuilder('p');

        if (!$isAdmin) {
          $qb->join('p.users', 'u')
             ->andWhere('u.id = ?1')
             ->setParameter(1, $user->getId());
        }
        $qb->orderBy('p.createdAt');

        return $qb;
      };

      $resolver->setDefaults(array('query_builder' => $queryBuilder));
  }

  public function getParent()
  {
      return EntityType::class;
  }
	
}