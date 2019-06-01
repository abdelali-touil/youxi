<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;
use App\Service\EntityFactory;
use App\Repository\GameRepository;
use App\Entity\Game;

/**
 * @Route("/games")
 */
class GameController extends AbstractController{
    
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer) 
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/", name="games_list", methods="GET")
     */
    public function index(GameRepository $gameRepository): ?JsonResponse 
    {
        $games = $gameRepository->findBy([
            'isArchived' => false
        ], [
            'name' => 'ASC'
        ]);
        $jsonContent = $this->serializer->serialize($games, 'json');

        return new JsonResponse([
            'games' => $jsonContent
        ], Response::HTTP_OK); 
    }

    /**
     * @Route("/{id}", name="game_show", methods="GET")
     */
    public function show(Game $game): ?JsonResponse 
    {
        $jsonContent = $this->serializer->serialize($game, 'json');
        
        return new JsonResponse([
            'game' => $jsonContent
        ], Response::HTTP_OK); 
    }
    
    /**
     * @Route("/create", name="game_create", methods="PUT")
     */
    public function create(Request $request, EntityFactory $entityFactory): ?JsonResponse 
    {
        $game = $entityFactory->bind($request, Game::class)
                              ->create();
        try {
            $this->entityManager->persist($game);
            $this->entityManager->flush();

        } catch (DBALException $e) {
            throw $e;
        }
        $jsonContent = $this->serializer->serialize($game, 'json');

        return new JsonResponse([
            'game' => $jsonContent
        ], Response::HTTP_OK);
    }

    public function update(): ?JsonResponse 
    {

    }

    /**
     * @Route("/delete/{id}", name="game_delete", methods="DELETE")
     */
    public function delete(Game $game): ?JsonResponse 
    {
        try {
            $this->entityManager->remove($game);
            $this->entityManager->flush();

        } catch (DBALException $e) {
            throw $e;
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}