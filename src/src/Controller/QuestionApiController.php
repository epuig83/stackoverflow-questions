<?php

namespace App\Controller;

use App\Exception\StackOverFlowException;
use App\Service\Questions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class QuestionApiController extends AbstractController
{
    /**
     * @param string $tagged
     * @param Request $request
     * @param Questions $question
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function question(string $tagged, Request $request, Questions $question): Response
    {
        try {
            $question->addFilters(
                $tagged,
                $request->query->get('fromdate'),
                $request->query->get('todate'),
            );

            return $this->json([
                    'success' => true,
                    'data' => $question->fetchQuestions()
                ],
                Response::HTTP_OK
            );
        } catch (StackOverFlowException $e) {
            return $this->json([
                  'success' => false,
                  'errors' => $e->getMessage()
                ],
                Response::HTTP_OK
            );
        }
    }
}
