<?php

namespace France\TvBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

class DefaultController extends AbstractApiController
{
    /**
     * @Route("/{username}/{password}")
     */
    public function indexAction(Request $request, $username, $password)
    {

        try {
            $client = $this->get('m6web_guzzlehttp_other');

            $res = $client->post(
                '/api/login_check',
                array(
                    '_username' => $username,
                    '_password' => $password,
                )
            );


            $status = $res->getStatusCode();
            $body = $res->getBody();
            $result = $body->getContents();

//            $item   = \GuzzleHttp\json_decode($result);

            return new Response($result);

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // connection problem like timeout
            dump($e->getRequest());
            dump($e->getResponse());
            dump($e);

            die('eeee');
        }

    }
    /**
     *
     * Create Token
     *
     *
     *  <ul>
     *      <li>_username</li>
     *      <li>_password</li>
     *  </ul>
     *
     * @ApiDoc(
     *     description="Create new tocken",
     *     section="Login",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         201="Ok Created",
     *         500="NOK, Server error"
     *     },
     *     views = { "default", "user2" },
     *    requirements={
     *          {"name"="_username", "dataType"="string", "requirement"="[A-Z]{2}", "description"="username"},
     *          {"name"="_password", "dataType"="password", "requirement"="[a-z]{2}", "description"="password"}
     *      }
     *  )
     * @param Request $request
     * @Rest\Post("/api/login_check")
     * @return Response
     **/
    public function cloginAction(Request $request)
    {
    }

}
