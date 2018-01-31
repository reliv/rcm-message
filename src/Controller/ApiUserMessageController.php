<?php

namespace RcmMessage\Controller;

use Doctrine\ORM\EntityManager;
use Rcm\Http\Response;
use Rcm\View\Model\ApiJsonModel;
use RcmMessage\Api\GetCurrentUserId;
use RcmMessage\Api\GetServerRequest;
use RcmMessage\Api\IsAllowed;
use RcmMessage\Api\PrepareMessageForDisplay;
use RcmMessage\Entity\Message;
use RcmMessage\Entity\UserMessage;
use RcmMessage\Entity\UserMessageInterface;
use Reliv\RcmApiLib\Controller\AbstractRestfulJsonController;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ApiUserMessageController extends AbstractRestfulJsonController
{
    protected $entityManager;
    protected $isAllowed;
    protected $getServerRequest;
    protected $getCurrentUserId;
    protected $prepareMessageForDisplay;
    protected $isAllowedOptions;

    /**
     * @param EntityManager    $entityManager
     * @param IsAllowed        $isAllowed
     * @param GetServerRequest $getServerRequest
     * @param GetCurrentUserId $getCurrentUserId
     * @param array            $isAllowedOptions
     */
    public function __construct(
        EntityManager $entityManager,
        IsAllowed $isAllowed,
        GetServerRequest $getServerRequest,
        GetCurrentUserId $getCurrentUserId,
        PrepareMessageForDisplay $prepareMessageForDisplay,
        $isAllowedOptions = []
    ) {
        $this->entityManager = $entityManager;
        $this->isAllowed = $isAllowed;
        $this->getServerRequest = $getServerRequest;
        $this->getCurrentUserId = $getCurrentUserId;
        $this->prepareMessageForDisplay = $prepareMessageForDisplay;
        $this->isAllowedOptions = $isAllowedOptions;
    }

    /**
     * getEntityManager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * getUserMessageRepository
     *
     * @return \RcmMessage\Repository\UserMessage|\Doctrine\ORM\EntityRepository
     */
    protected function getUserMessageRepository()
    {
        return $this->entityManager->getRepository(
            \RcmMessage\Entity\UserMessage::class
        );
    }

    /**
     * @param UserMessageInterface $userMessage
     * @param array                $options
     *
     * @return UserMessageInterface
     */
    protected function prepareUserMessageForDisplay(
        UserMessageInterface $userMessage,
        array $options = []
    ) {
        $message = $this->prepareMessageForDisplay->__invoke(
            $userMessage->getMessage()
        );
        $userMessage->setMessage($message);

        return $userMessage;
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

        $serverRequest = $this->getServerRequest->__invoke();

        $currentUserId = $this->getCurrentUserId->__invoke(
            $serverRequest
        );

        if (empty($currentUserId)) {
            return false;
        }

        if ($currentUserId == $userId) {
            return true;
        }

        // ACCESS CHECK if not current user
        return $this->isAllowed->__invoke(
            $serverRequest,
            $this->isAllowedOptions
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

        $userMessages = $userMessageRepo->findBy(['userId' => $userId]);

        $results = [];
        /** @var UserMessage $userMessage */
        foreach ($userMessages as $userMessage) {
            $userMessage = $this->prepareUserMessageForDisplay(
                $userMessage
            );
            $results[] = $userMessage->toArray();
        }

        return $this->getApiResponse(
            $results
        );
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

        /** @var UserMessage $userMessage */
        $userMessage = $userMessageRepo->findOneBy(
            [
                'id' => $id,
                'userId' => $userId
            ]
        );

        if (empty($userMessage)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);

            return $this->getApiResponse(
                $userMessage->toArray(),
                404
            );
        }

        $userMessage = $this->prepareUserMessageForDisplay(
            $userMessage
        );

        return $this->getApiResponse($userMessage->toArray());
    }

    /**
     * create
     *
     * @param mixed $data
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse|\Reliv\RcmApiLib\Http\ApiResponseInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($data)
    {
        $serverRequest = $this->getServerRequest->__invoke();

        $allowed = $this->isAllowed->__invoke(
            $serverRequest,
            $this->isAllowedOptions
        );

        if (!$allowed) {
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

        $entityManager->persist($newUserMessage);
        $entityManager->flush($newMessage);
        $entityManager->flush($newUserMessage);

        $newUserMessage = $this->prepareUserMessageForDisplay(
            $newUserMessage
        );

        return $this->getApiResponse($newUserMessage->toArray());
    }

    /**
     * update
     *
     * @param string $id
     * @param mixed  $data
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse|\Reliv\RcmApiLib\Http\ApiResponseInterface
     * @throws \Doctrine\ORM\OptimisticLockException
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

        /** @var UserMessage $userMessage */
        $userMessage = $userMessageRepo->findOneBy(
            [
                'id' => $id,
                'userId' => $userId
            ]
        );

        if (empty($userMessage)) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);

            return $this->getApiResponse($userMessage->toArray(), 404);
        }

        $userMessage->populate($data, ['id', 'dateViewed', 'message']);

        $entityManager = $this->getEntityManager();

        $entityManager->persist($userMessage);
        $entityManager->flush($userMessage);

        $userMessage = $this->prepareUserMessageForDisplay(
            $userMessage
        );

        return $this->getApiResponse($userMessage->toArray());
    }
}
