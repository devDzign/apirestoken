France tv Api Symfony 3
======

A Symfony project created on November 3, 2016, 15:17 am.


Clone project :
---------------

      git clone https://github.com/devdzmc/ftv.git
      cd ftv
      composer install or composer update
      
Step for install Project :
--------------------------

     php bin/console doctrine:database:create
     php bin/console doctrine:schema:create 
     php bin/console doctrine:fixtures:load -n 
     

Url de test de Api FR TV gestion article :
-----------------------------------------

    http://localhost/ftv/web/api/doc
    http://localhost/ftv/web/app_dev.php/api/doc
    
Bundle Used :
-------------
    new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle()
    new FOS\RestBundle\FOSRestBundle()
    new JMS\SerializerBundle\JMSSerializerBundle()
    new Nelmio\ApiDocBundle\NelmioApiDocBundle()
    new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle()
    
   
Tocken used:
-----------------

    Authorization: Bearer {token}