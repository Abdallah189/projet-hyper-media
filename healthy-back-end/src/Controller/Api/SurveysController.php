<?php


namespace App\Controller\Api;


use App\Entity\Question;
use App\Entity\Survey;
use App\Repository\SurveyRepository;
use App\Repository\UserRepository;
use App\Services\TokenDecoder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

class SurveysController extends AbstractController
{
    /**
     * @Route("/api/survey/add",name="add_survey",methods={"POST"})
     */
    public function addSurvey (Request $request,UserRepository $userRepository,SerializerInterface $serializer,EntityManagerInterface $manager,MessageBusInterface $bus):JsonResponse{
        $data = $request->getContent();

        try {
            $tokenDecoder = new TokenDecoder($request);
            $roles = $tokenDecoder->getRoles();
            if (!in_array('ROLE_USER',$roles,true)){
                return $this->json([
                    'message' => 'Access Denied !',
                    'status' => 403
                ],403);
            }
          //  $survey = $serializer->deserialize($data,Survey::class,'json');
            $da = json_decode($data, true);
            $survey = $serializer->denormalize($data,Survey::class,'json');
            $survey->setCreatedAt($da['createdAt']);
            $survey->setFeedback($da['feedback']);
            foreach ($da['questions'] as $question) {
                $q = new Question();
                $q->setTitle($question['title']);
                $q->setResponse($question['response']);
                $survey->addQuestion($q);
            }
            $email = $tokenDecoder->getEmail();
            $user = $userRepository->findOneBy(['email' => $email]);
            $survey->setCreatedBy($user);
            $manager->persist($survey);
            $manager->flush();
            $update = new Update("http://127.0.0.1:8001/",$serializer->serialize($survey,"json",['groups' => 'read_survey']));
            $bus->dispatch($update);
            return $this->json([
                'status' => 201,
                'message' => 'Survey created'
            ],201);
        }catch (NotEncodableValueException $exception){
            return $this->json([
                'status' => 400,
                'message' => $exception->getMessage()
            ], 400);
        }
    }
    /**
     * @Route("/api/surveys",name="get_surveys",methods={"GET"})
     */
    public function getSurveys (Request $request,SurveyRepository $surveyRepository):JsonResponse{
        $data = $request->getContent();
        try {
            $tokenDecoder = new TokenDecoder($request);
            $roles = $tokenDecoder->getRoles();
            if(!in_array('ROLE_USER',$roles,true)){
                return $this->json([
                    'status' => 401,
                    'message' => 'Access denied'
                ],401);
            }
            $surveys = $surveyRepository->findAll();
            return $this->json($surveys,200, [], ['groups' => 'read_survey']);
        }catch (NotEncodableValueException $exception){
            return $this->json([
                'status' => 400,
                'message' => $exception->getMessage()
            ],400);
        }
    }
    /**
     * @Route("/api/survey/delete/{id}",name="delete",methods={"DELETE"})
     */
    public function deleteSurvey ($id,Request $request,SurveyRepository $surveyRepository,EntityManagerInterface $manager):JsonResponse{
        $data = $request->getContent();
        $survey = $surveyRepository->findOneBy(['id' => $id]);
        if(!$survey){
            return $this->json([
                'status' => 404,
                'message' => 'page not found !'
            ],404);
        }
        $tokenDecoder = new TokenDecoder($request);
        $roles = $tokenDecoder->getRoles();
        if(in_array('ROLE_USER',$roles,true) || in_array('ROLE_DOCTOR',$roles,true)){
            return $this->json([
                'status' => 401,
                'message' => 'Access denied'
            ],401);
        }
        $manager->remove($survey);
        $manager->flush();
        return $this->json([
            'status' => 201,
            'message' => 'Survey deleted'
        ],201);
    }
}