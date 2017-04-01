<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Recipe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends Controller
{
    /**
     * @Route("/api/recipes/{recipe}", name="recipe_get")
     * @ParamConverter("recipe", class="AppBundle:Recipe")
     */
    public function getAction(Recipe $recipe)
    {
        return new Response(
            $this->get('serializer')->serialize($recipe, 'json'),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @Route("/api/recipes", name="recipe_index")
     */
    public function indexAction(Request $request)
    {
        $queryParams = [
            'author_id' => null,
        ];

        foreach ($queryParams as $key => $dummy) {
            $queryParams[$key] = $request->query->get('author_id', null);
        }

        $recipes = $this->getRecipeRepository()->findLatest($queryParams);

        return new Response(
            $this->get('serializer')->serialize($recipes, 'json'), Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @return RecipeRepository
     */
    private function getRecipeRepository()
    {
        return $this->container->get('repository.recipe');
    }
}