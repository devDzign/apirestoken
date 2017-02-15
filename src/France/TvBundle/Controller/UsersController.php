<?php

namespace France\TvBundle\Controller;


use France\TvBundle\Entity\Article;
use France\TvBundle\Entity\User;
use France\TvBundle\Form\ArticleType;
use France\TvBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;


class UsersController extends AbstractApiController
{
    /**
     * return List users
     *
     *
     *
     * @ApiDoc(
     *     description="list of users",
     *     section="Api gestion users",
     *     tags={
     *     "done"="#10A54A",
     *     "need validations" = "#ff0000"
     *     },
     *     statusCodes={
     *         200="Ok",
     *         500="NOK, Server error"
     *     }
     *  )
     * @Rest\View(serializerGroups={"test"})
     * @Rest\Get("/")
     *
     * @return Response
     * */
    public function getUsersAction()
    {
        try {
            $em    = $this->getDoctrine();
            $users = $em->getRepository("TvBundle:User")->FindAll();
            
            return $this->sendResponseSuccess($users);
        } catch (\Exception $exc) {
            $this->get('logger')->error($exc->getMessage(), ['Trace' => $exc->getTraceAsString()]);
            
            return $this->sendResponseError("An error occured. Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
    
    
    /**
     *
     * get content of user <id>
     *
     * type of user
     *  <ul>
     *      <li>id User</li>
     *  </ul>
     *
     * @ApiDoc(
     *     description="get user by id",
     *     section="Api gestion users",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         200="Ok",
     *         500="NOK, Server error"
     *     },
     *    requirements={
     *        {
     *           "name"="id",
     *           "dataType"="integer",
     *           "requirement"="\d+",
     *           "description"="The user's id"
     *        }
     *
     *      }
     *  )
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/{id}")
     *
     * @return Response
     * */
    public function getUserAction(Request $request)
    {
        try {
            
            if (empty($request->get('id')) || $request->get('id') == "{id}") {
                
                return $this->sendResponseError("You must give id ",
                    Response::HTTP_NOT_FOUND,
                    Response::HTTP_NOT_FOUND);
            }
            $em   = $this->getDoctrine();
            $user = $em->getRepository("TvBundle:User")->find($request->get('id'));
            
            if (empty($user)) {
                return $this->sendResponseError("l'utilisateur id: " . $request->get('id') . " n'existe pas ",
                    Response::HTTP_NOT_FOUND,
                    Response::HTTP_NOT_FOUND);
            }
            
            return $this->sendResponseSuccess($user);
            
        } catch (\Exception $exc) {
            $this->get('logger')->error($exc->getMessage(), ['Trace' => $exc->getTraceAsString()]);
            
            return $this->sendResponseError("An error occured. Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     *
     * Create one user
     *
     * type of user
     *  <ul>
     *      <li> title</li>
     *      <li> body </li>
     *      <li> leading </li>
     *  </ul>
     *
     * @ApiDoc(
     *     description="Create new user",
     *     section="Api gestion users",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         201="Ok Created",
     *         500="NOK, Server error"
     *     },
     *    requirements={
     *          {"name"="firstname", "dataType"="string", "requirement"="[A-Z]{2}", "description"="firstname"},
     *          {"name"="lastname", "dataType"="string", "requirement"="[a-z]{2}", "description"="lastname"},
     *          {"name"="email", "dataType"="email", "description"="email"}
     *      },
     *     input = {"class" = "france_tvbundle_user"},
     *     output={"class"="France\TvBundle\Entity\User"}
     *  )
     * @Rest\Post("/")
     * @param Request $request
     * @return Response
     * */
    public function cpostUsersAction(Request $request)
    {
        
        try {
            $user = new User();
            $form = $this->createForm(
                UserType::class,    // FormType
                $user,          // Entity
                ['validation_groups'=>['Default', 'New']]
            );
            
            $form->submit($request->request->all());
            
            if ($form->isValid()) {
                $encoder = $this->get('security.password_encoder');
                // le mot de passe en claire est encodé avant la sauvegarde
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);

                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($user);
                $em->flush();
                
                return $this->sendResponseSuccess($user, Response::HTTP_CREATED);
            } else {
                
                return $this->sendResponseSuccess($form, Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $exc) {
            $this->get('logger')->error($exc->getMessage(), ['Trace' => $exc->getTraceAsString()]);
            
            return $this->sendResponseError("An error occured. Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        
    }


    /**
     * Remove one user
     *
     * type of user
     * @ApiDoc(
     *     description="Remove user by id",
     *     section="Api gestion users",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         200="Ok",
     *         500="NOK, Server error"
     *     },
     *    requirements={
     *           {
     *               "name"="id",
     *               "dataType"="integer",
     *               "requirement"="\d+",
     *               "description"="The user's id"
     *           }
     *      }
     *  )
     * @Rest\Delete("/{id}")
     *
     * @return Response
     * */
    public function removeUsersAction(Request $request)
    {

        try {

            $em = $this->getDoctrine();

            if (empty($request->get('id')) || $request->get('id') == "{id}") {
                return $this->sendResponseError("You must give id ",
                    Response::HTTP_NOT_FOUND,
                    Response::HTTP_NOT_FOUND);
            }

            $user = $em->getRepository("TvBundle:User")->find($request->get('id'));

            if (empty($user)) {
                return $this->sendResponseError("l'article id: " . $request->get('id') . " n'existe pas ",
                    Response::HTTP_NOT_FOUND,
                    Response::HTTP_NOT_FOUND);
            }

            $em->getManager()->remove($user);
            $em->getManager()->flush();

            return $this->sendResponseSuccess($user, Response::HTTP_OK);
        } catch (\Exception $exc) {
            $this->get('logger')->error($exc->getMessage(), ['Trace' => $exc->getTraceAsString()]);

            return $this->sendResponseError("An error occured. Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Update one user
     *
     * type of user
     * @ApiDoc(
     *     description="Remove user by id",
     *     section="Api gestion users",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         200="Ok",
     *         500="NOK, Server error"
     *     },
     *    requirements={
     *           {
     *               "name"="id",
     *               "dataType"="integer",
     *               "requirement"="\d+",
     *               "description"="The user's id"
     *           }
     *      }
     *  )
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Put("/users/{id}")
     */
    public function updateUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }


    /**
     * Update partial one user
     *
     * type of user
     * @ApiDoc(
     *     description="Remove user by id",
     *     section="Api gestion users",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         200="Ok",
     *         500="NOK, Server error"
     *     },
     *    requirements={
     *           {
     *               "name"="id",
     *               "dataType"="integer",
     *               "requirement"="\d+",
     *               "description"="The user's id"
     *           }
     *      }
     *  )
     * @Rest\View(serializerGroups={"test"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }


    private function updateUser(Request $request, $clearMissing)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('TvBundle:User')
            ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $user User */

        if (empty($user)) {
            return $this->userNotFound();
        }

        if ($clearMissing) { // Si une mise à jour complète, le mot de passe doit être validé
            $options = ['validation_groups'=>['Default', 'FullUpdate']];
        } else {
            $options = []; // Le groupe de validation par défaut de Symfony est Default
        }

        $form = $this->createForm(UserType::class, $user, $options);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            // Si l'utilisateur veut changer son mot de passe
            if (!empty($user->getPlainPassword())) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }
            $em = $this->get('doctrine.orm.entity_manager');
            $em->merge($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }

    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }



}
