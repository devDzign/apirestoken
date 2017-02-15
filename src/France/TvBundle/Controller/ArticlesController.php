<?php

namespace France\TvBundle\Controller;


use France\TvBundle\Entity\Article;
use France\TvBundle\Form\ArticleType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;


class ArticlesController extends AbstractApiController
{
    /**
     * return List Articles
     *  get liste
     * type of radio
     *  <ul>
     *      <li> genre</li>
     *      <li> mood </li>
     *      <li> decade</li>
     *  </ul>
     *  ### Response ###
     *
     *     {
     *          "idDecision": "Id de la decision créée",
     *          "publicUrl": "Url si decision public"
     *     }
     *
     * @ApiDoc(
     *     description="list of Articles",
     *     views = { "default", "premium" },
     *     section="Api gestion Article",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         200="Ok",
     *         500="NOK, Server error"
     *     },
     *      filters={
     *        {"name"="a-filter", "dataType"="num", "value"={"arg1":"aaaa", "arg2":"ccc"}},
     *        {"name"="another-filter", "dataType"="num", "pattern"="(foo|bar) ASC|DESC"}
     *     },
     *
     *  )
     *
     * @Rest\Get("/")
     *
     * @return Response
     * */
    public function getArticlesAction()
    {
        try {

            $em       = $this->getDoctrine();
            $articles = $em->getRepository("TvBundle:Article")->myFindAll();
            
            return $this->sendResponseSuccess($articles);
        } catch (\Exception $exc) {
            $this->get('logger')->error($exc->getMessage(), ['Trace' => $exc->getTraceAsString()]);
            
            return $this->sendResponseError("An error occured. Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
    
    /**
     *
     * get content of article <id>
     *
     * type of Article
     *  <ul>
     *      <li>id Article</li>
     *  </ul>
     *
     * @ApiDoc(
     *     description="get Article by id",
     *     section="Api gestion Article",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         200="Ok",
     *         500="NOK, Server error"
     *     },
     *    requirements={
     *        {
     *           "name"="id",
     *           "dataType"="Choice",
     *           "requirement"="\d+",
     *           "description"="The article's id"
     *        }
     *
     *      }
     *  )
     *
     * @Rest\Get("/{id}")
     *
     * @return Response
     * */
    public function getArticleAction($id)
    {
        try {
            
            if (empty($id) || $id == "{id}") {
                
                return $this->sendResponseError("You must give id ",
                    Response::HTTP_NOT_FOUND,
                    Response::HTTP_NOT_FOUND);
            }
            $em      = $this->getDoctrine();
            $article = $em->getRepository("TvBundle:Article")->find($id);
            
            if (empty($article)) {
                return $this->sendResponseError("l'article id: " . $id . " n'existe pas ",
                    Response::HTTP_NOT_FOUND,
                    Response::HTTP_NOT_FOUND);
            }
            
            return $this->sendResponseSuccess($article);
            
        } catch (\Exception $exc) {
            $this->get('logger')->error($exc->getMessage(), ['Trace' => $exc->getTraceAsString()]);
            
            return $this->sendResponseError("An error occured. Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    
    /**
     *
     * Create one Item
     *
     * type of Article
     *  <ul>
     *      <li> title</li>
     *      <li> body </li>
     *      <li> leading </li>
     *  </ul>
     *
     * @ApiDoc(
     *     description="Create new Article",
     *     section="Api gestion Article",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         201="Ok Created",
     *         500="NOK, Server error"
     *     },
     *    requirements={
     *          {"name"="title", "dataType"="string", "requirement"="[A-Z]{2}", "description"="titre Article"},
     *          {"name"="body", "dataType"="string", "requirement"="[a-z]{2}", "description"="(optional) contenu Article"},
     *          {"name"="leading", "dataType"="string", "requirement"="[a-z]{2}", "description"="(optional) text leading"}
     *      },
     *
     *     input = {
     *     "class" = "france_tvbundle_article"
     *     },
     *     output={"class"="France\TvBundle\Entity\Article"}
     *  )
     * @Rest\Post("/")
     * @param Request $request
     * @return Response
     * */
    public function cpostArticlesAction(Request $request)
    {
    
        try {
            $article = new Article();
            $form    = $this->createForm(
                ArticleType::class,    // FormType
                $article          // Entity
            );
        
            $form->submit($request->request->all());
        
            if ($form->isValid()) {
            
                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($article);
                $em->flush();
            
                return $this->sendResponseSuccess($article, Response::HTTP_CREATED);
            } else {
            
                return $this->sendResponseSuccess($form, Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $exc) {
            $this->get('logger')->error($exc->getMessage(), ['Trace' => $exc->getTraceAsString()]);
        
            return $this->sendResponseError("An error occured. Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    
    /**
     * Remove one Item
     *
     * type of Article
     *  <ul>
     *      <li> title</li>
     *      <li> body </li>
     *  </ul>
     *
     * @ApiDoc(
     *     description="Remove Article by id",
     *     section="Api gestion Article",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         200="Ok",
     *         500="NOK, Server error"
     *     },
     *    requirements={
     *           {"name"="id","dataType"="integer","requirement"="\d+","description"="The new's id"}
     *      }
     *  )
     * @Rest\Delete("/{id}")
     *
     * @return Response
     * */
    public function removeArticlesAction($id)
    {
        
        try {
            
            $em = $this->getDoctrine();
            
            if (empty($id) || $id == "{id}") {
                return $this->sendResponseError("You must give id ",
                    Response::HTTP_NOT_FOUND,
                    Response::HTTP_NOT_FOUND);
            }
            
            $article = $em->getRepository("TvBundle:Article")->find($id);
    
            if (empty($article)) {
                return $this->sendResponseError("l'article id: " . $id . " n'existe pas ",
                    Response::HTTP_NOT_FOUND,
                    Response::HTTP_NOT_FOUND);
            }
    
            $em->getManager()->remove($article);
            $em->getManager()->flush();
    
            return $this->sendResponseSuccess($article, Response::HTTP_OK);
        } catch (\Exception $exc) {
            $this->get('logger')->error($exc->getMessage(), ['Trace' => $exc->getTraceAsString()]);
            
            return $this->sendResponseError("An error occured. Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
    
    
    /**
     *
     * Put Update one Article
     *
     * type of Article
     *  <ul>
     *      <li> title</li>
     *      <li> body </li>
     *      <li> leading </li>
     *  </ul>
     *
     * @ApiDoc(
     *     description="Update Article",
     *     section="Api gestion Article",
     *     tags={ "done"="#10A54A" },
     *     statusCodes={
     *         201="Ok Created",
     *         500="NOK, Server error"
     *     },
     *    requirements={
     *          {"name"="title", "dataType"="string", "requirement"="[A-Z]{2}", "description"="titre Article"},
     *          {"name"="body", "dataType"="string", "requirement"="[a-z]{2}", "description"="(optional) contenu Article"},
     *          {"name"="leading", "dataType"="string", "requirement"="[a-z]{2}", "description"="(optional) text leading"}
     *      },
     *     input = {"class" = "france_tvbundle_article"},
     *     output={"class"="France\TvBundle\Entity\Article"}
     *  )
     * @Rest\Put("/{id}")
     * @param Request $request
     * @return Response
     * */
    public function putArticlesAction(Request $request)
    {
        
        try {
            
            $em = $this->get('doctrine.orm.entity_manager');
            
            $article = $em
                ->getRepository('TvBundle:Article')
                ->find($request->get('id'));
            
            
            if (empty($article)) {
                return $this->sendResponseError("l'article id: " . $request->get('id') . " n'existe pas ",
                    Response::HTTP_NOT_FOUND,
                    Response::HTTP_NOT_FOUND);
            }
            
            $form = $this->createForm(
                ArticleType::class,    // FormType
                $article          // Entity
            );
            
            $form->submit($request->request->all());
            
            if ($form->isValid()) {
                // l'entité vient de la base, donc le merge n'est pas nécessaire.
                // il est utilisé juste par soucis de clarté
                $em->merge($article);
                $em->flush();
                
                return $this->sendResponseSuccess($article, Response::HTTP_OK);
            } else {
                
                return $this->sendResponseSuccess($form);
            }
        } catch (\Exception $exc) {
            $this->get('logger')->error($exc->getMessage(), ['Trace' => $exc->getTraceAsString()]);
            
            return $this->sendResponseError("An error occured. Please try again.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        
    }
    
    
}
