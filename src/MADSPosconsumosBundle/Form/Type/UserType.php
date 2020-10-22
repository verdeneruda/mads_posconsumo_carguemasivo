<?php 

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

use Doctrine\ORM\EntityRepository;

/**
* class UserType
*
* @author David Alméciga <walmeciga@minambiente.gov.co>
*/
class UserType extends AbstractType
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

      $queryBuilder = function (Options $options, $configs) use ($user, $isAdmin) {

        $qb = $options['em']->getRepository($options['class'])->createQueryBuilder('u')
                 ->where('u.enabled = 1');

        if (!$isAdmin && $options['by_program']) {
          $qb->andWhere('u.program = ?1')
             ->setParameter(1, $user->getProgram()->getId());
        }
        $qb->orderBy('u.createdAt');

        return $qb;
      };

      $resolver->setRequired('by_program');
      $resolver->setNormalizer('query_builder', $queryBuilder);
  }

  public function getParent()
  {
      return EntityType::class;
  }
	
}