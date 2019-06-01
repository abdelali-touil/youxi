<?php
namespace App\Controller\Tc\Crud;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\EntityFactory;
use App\Repository\GameRepository;
use App\Entity\Game;

interface CrudInterface {

    public function index(GameRepository $gameRepository): ?JsonResponse;

    public function show(Game $game): ?JsonResponse;
    
    public function create(Request $request, EntityFactory $entityFactory): ?JsonResponse;

    public function update(): ?JsonResponse;

    public function delete(Game $game): ?JsonResponse;
}