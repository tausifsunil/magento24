<?php

namespace BkFootware\Newsletter\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\Http;

class NewsletterField implements ObserverInterface
{
    /**
     * Model http
     *
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * Model subscriber
     *
     * @var \Magento\Newsletter\Model\Subscriber
     */
    protected $subscriberFactory;

    /**
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param Http $request
     */
    public function __construct(
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        Http $request
    ) {
        $this->subscriberFactory = $subscriberFactory;
        $this->request = $request;
    }

    /**
     * save the data of addtional field
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $subscriber = $observer->getEvent()->getSubscriber();
        $name = $this->request->getPost('name');

        $subscriber->setName($name);

        return $this;
    }
}
