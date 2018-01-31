<?php

namespace RcmMessage\Api;

use RcmMessage\Entity\MessageInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PrepareMessageForDisplayComposite implements PrepareMessageForDisplay
{
    /**
     * @var array [['service' => '', 'priority' => 0]]
     */
    protected $prepareMessageForDisplayServices = [];

    /**
     * @param PrepareMessageForDisplay $prepareMessageForDisplay
     * @param int                      $priority
     *
     * @return void
     */
    public function add(PrepareMessageForDisplay $prepareMessageForDisplay, $priority = 0)
    {
        $this->prepareMessageForDisplayServices[] = [
            'service' => $prepareMessageForDisplay,
            'priority' => $priority,
        ];
    }

    /**
     * @param MessageInterface $message
     * @param array            $options
     *
     * @return MessageInterface
     */
    public function __invoke(
        MessageInterface $message,
        array $options = []
    ): MessageInterface {
        $queue = new \SplPriorityQueue();

        foreach ($this->prepareMessageForDisplayServices as $prepareMessageForDisplayService) {
            $queue->insert(
                $prepareMessageForDisplayService['service'],
                $prepareMessageForDisplayService['priority']
            );
        }

        foreach ($queue as $prepareMessageForDisplayService) {
            $message = $prepareMessageForDisplayService->__invoke(
                $message,
                $options
            );
        }

        return $message;
    }
}
