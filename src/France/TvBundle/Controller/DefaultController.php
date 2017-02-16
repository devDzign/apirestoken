<?php

namespace France\TvBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
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
     * @Route("/test")
     */
    public function testAction(Request $request)
    {


        try {
            $client = $this->get('m6web_guzzlehttp_other');

            $res = $client->get(
                '/app_dev.php/search',
                array(
                    'query' => ['query' => 't'],
                )
            );


            $status = $res->getStatusCode();
            $header = $res->getHeaders();
            $header2 = $res->getReasonPhrase();
            $body = $res->getBody();
            $result = $body->getContents();


            return new JsonResponse($result, $status, $header, true);

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // connection problem like timeout
            dump($e->getRequest());
            dump($e->getResponse());
            dump($e);

            die('eeee');
        }

    }

    /**
     * @Route("/", name="homepage")
     */
    public function test2Action(Request $request)
    {

        return $this->render('TvBundle:Default:index.html.twig');
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
