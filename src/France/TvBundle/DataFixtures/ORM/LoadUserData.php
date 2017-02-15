<?php
/**
 * Created by PhpStorm.
 * User: mchabour
 * Date: 15/02/2017
 * Time: 12:17
 */

namespace TvBundle\DataFixtures\ORM;



use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use France\TvBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadUserData implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('api');
        $userAdmin->setEmail('api@test.fr');
        $userAdmin->setSalt(md5(uniqid()));

        // the 'security.password_encoder' service requires Symfony 2.6 or higher
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($userAdmin, 'apidoc');
        $userAdmin->setPassword($password);

        $userAdmin->addRole('ROLE_ADMIN');
        $userAdmin->addRole('ROLE_USER');
        $userAdmin->addRole('ROLE_USERS_R');
        $userAdmin->addRole('ROLE_USERS_D');
        $userAdmin->addRole('ROLE_USERS_U');
        $userAdmin->setEnabled(true);

        $manager->persist($userAdmin);
        $manager->flush();
    }
}