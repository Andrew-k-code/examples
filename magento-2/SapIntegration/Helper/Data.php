<?php

namespace AndrewKode\SapIntegration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    /**
     * @var Context
     */
    protected $context;

    public function __construct(Context $context)
    {
        parent::__construct($context);
        $this->context = $context;
    }
}
