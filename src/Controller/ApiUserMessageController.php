<?php

namespace RcmMessage\Controller;

use Rcm\Acl\ResourceName;
use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use RcmMessage\Entity\Message;
use RcmMessage\Entity\UserMessage;
use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\GetPsrRequest;
use RcmUser\Service\RcmUserService;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiUserMessageController extends AbstractRestfulController
{
    /**
     * getEntityManager
     *
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->serviceLocator->get('Doctrine\ORM\EntityManager');
    }

    /**
     * getUserMessageRepository
     *
     * @return \RcmMessage\Repository\UserMessage
     */
    protected function getUserMessageRepository()
    {
        return $this->getEntityManager()->getRepository(
            \RcmMessage\Entity\UserMessage::class
        );
    }

    /**
     * getCurrentUser
     *
     * @return \RcmUser\User\Entity\UserInterface
     */
    protected function getCurrentUser()
    {
        /** @var GetIdentity $getIdentity */
        $getIdentity = $this->serviceLocator->get(GetIdentity::class);

        $psrRequest = GetPsrRequest::invoke();

        return $getIdentity->__invoke(
            $psrRequest
        );
    }

    /**
     * canAccess
     *
     * @return bool
     */
    protected function canAccess()
    {
        $userId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'userId',
                null
            );

        $currentUser = $this->getCurrentUser();

        if (empty($currentUser)) {
            return false;
        }

        if ($currentUser->getId() == $userId) {
            return true;
        }
        /** @var RcmUserService $rcmUserService */
        $rcmUserService = $this->serviceLocator->get(RcmUserService::class);

        //ACCESS CHECK if not current user
        return $rcmUserService->isAllowed(
            ResourceName::RESOURCE_SITES,
            'admin'
        );
    }

    /**
     * getList
     *
     * @return ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function getList()
    {
        if (!$this->canAccess()) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        $userMessageRepo = $this->getUserMessageRepository();
        $userId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'userId',
                null
            );

        $messages = $userMessageRepo->findBy(['userId' => $userId]);

        $results = [];
        /** @var Message $message */
        foreach ($messages as $message) {
            $results[] = $message->toArray();
        }

        return new ApiJsonModel($results);
    }

    /**
     * get
     *
     * @param mixed $id
     *
     * @return ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function get($id)
    {
        if (!$this->canAccess()) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        $userMessageRepo = $this->getUserMessageRepository();
        $userId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'userId',
                null
            );

        /** @var Message $message */
        $message = $userMessageRepo->findOneBy(
            [
                'id' => $id,
                'userId' => $userId
            ]
        );

        if (empty($message)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);

            return new ApiJsonModel($message->toArray(), 404, 'Not Found');
        }

        return new ApiJsonModel($message->toArray());
    }

    /**
     * create
     *
     * @param mixed $data
     *
     * @return ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function create($data)
    {
        /** @var RcmUserService $rcmUserService */
        $rcmUserService = $this->serviceLocator->get(RcmUserService::class);

        if (!$rcmUserService->isAllowed(
            ResourceName::RESOURCE_SITES,
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        $userId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'userId',
                null
            );

        $newUserMessage = new UserMessage($userId);

        $newUserMessage->populate($data, ['id', 'dateViewed', 'message']);

        // @todo Should we force creation????
        $newMessage = new Message();

        $newMessage->populate($data['message'], ['id', 'dateCreated']);

        $newUserMessage->setMessage($newMessage);

        $entityManager = $this->getEntityManager();

        try {
            $entityManager->persist($newUserMessage);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new ApiJsonModel([], 1, $e->getMessage());
        }

        return new ApiJsonModel($newUserMessage->toArray());
    }

    /**
     * update
     *
     * @param string $id
     * @param mixed  $data
     *
     * @return ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function update($id, $data)
    {
        if (!$this->canAccess()) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        $userMessageRepo = $this->getUserMessageRepository();
        $userId = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'userId',
                null
            );

        /** @var Message $message */
        $message = $userMessageRepo->findOneBy(
            [
                'id' => $id,
                'userId' => $userId
            ]
        );

        if (empty($message)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);

            return new ApiJsonModel($message->toArray(), 404, 'Not Found');
        }

        $message->populate($data, ['id', 'dateViewed', 'message']);

        $entityManager = $this->getEntityManager();

        try {
            $entityManager->persist($message);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new ApiJsonModel([], 1, $e->getMessage());
        }

        return new ApiJsonModel($message->toArray());
    }
}
