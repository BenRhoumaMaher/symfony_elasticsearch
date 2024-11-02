<?php

namespace App\Controller;

use Elastica\Query;
use Elastica\Query\MatchQuery;
use Symfony\Component\HttpFoundation\Request;
use FOS\ElasticaBundle\Finder\FinderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * Constructor injection of FinderInterface to enable book search.
     *
     * @param FinderInterface $bookFinder The service for searching books with Elastica.
     */
    public function __construct(private FinderInterface $bookFinder)
    {
    }

    /**
     * Route for searching books by title.
     *
     * @Route("/search", name="app_search")
     * @param Request $request The HTTP request, which contains the search query.
     * @return Response The rendered search results.
     */
    #[Route('/search', name: 'app_search')]
    public function index(Request $request): Response
    {
        $query = $request->query->get('query', '');
        $books = [];

        if ($query) {
            $matchQuery = new MatchQuery();
            $matchQuery->setFieldQuery('title', $query);
            $matchQuery->setFieldFuzziness('title', 'AUTO');

            $elasticaQuery = new Query();
            $elasticaQuery->setQuery($matchQuery);

            $books = $this->bookFinder->find($elasticaQuery);
        }

        return $this->render(
            'search/index.html.twig',
            [
            'books' => $books,
            'query' => $query,
            ]
        );
    }
}
