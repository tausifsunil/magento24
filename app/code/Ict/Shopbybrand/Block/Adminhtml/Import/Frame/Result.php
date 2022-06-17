<?php
/**
 * @author Ict Team
 * @copyright Copyright (c) 2017 Ict (http://icreativetechnologies.com/)
 * @package Ict_Shopbybrand
 */

namespace Ict\Shopbybrand\Block\Adminhtml\Import\Frame;

use Magento\Framework\View\Element\Template;

class Result extends \Magento\Backend\Block\Template
{

    protected $_actions = [
        'clear' => [],
        'innerHTML' => [],
        'value' => [],
        'show' => [],
        'hide' => [],
        'removeClassName' => [],
        'addClassName' => [],
    ];

    protected $fields = [
        "Name",
        "Url Key",
        "Banner Text",
        "Logo",
        "Banner Logo",
        "Is Active",
        "Is Featured"
    ];

    public $fields_require = [
        ["key" => "Name", "position" => 0, "regular_expression" => "/^[a-zA-Z\s]+$/i", 'message' => "Category name contains only letters"],
        ["key" => "Is Active", "position" => 1, "regular_expression" => "/^[a-zA-Z\s]+$/i", 'message' => "Is Active contains only letters"]
    ];


    public $_messages = ['error' => [], 'success' => [], 'notice' => []];

    protected $_jsonEncoder;

    protected $_fileCsv;

    protected $import;

    public $uploaded_file;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\File\Csv $fileCsv,
        array $data = []
    ) {
        $this->_jsonEncoder = $jsonEncoder;
        $this->_fileCsv = $fileCsv;
        parent::__construct($context, $data);
    }

    /**
     * Add action for response.
     * @param string $actionName
     * @param string $elementId
     * @param mixed $value OPTIONAL
     * @return $this
     */
    public function addAction($actionName, $elementId, $value = null)
    {
        if (isset($this->_actions[$actionName])) {
            if (null === $value) {
                if (is_array($elementId)) {
                    foreach ($elementId as $oneId) {
                        $this->_actions[$actionName][] = $oneId;
                    }
                } else {
                    $this->_actions[$actionName][] = $elementId;
                }
            } else {
                $this->_actions[$actionName][$elementId] = $value;
            }
        }
        return $this;
    }

    /**
     * Add error message.
     * @param string $message Error message
     * @return $this
     */
    public function addError($message)
    {
        if (is_array($message)) {
            foreach ($message as $row) {
                $this->addError($row);
            }
        } else {
            $this->_messages['error'][] = $message;
        }
        return $this;
    }

    /**
     * Add notice message.
     * @param string[]|string $message Message text
     * @param bool $appendImportButton OPTIONAL Append import button to message?
     * @return $this
     */
    public function addNotice($message, $appendImportButton = false)
    {
        if (is_array($message)) {
            foreach ($message as $row) {
                $this->addNotice($row);
            }
        } else {
            $this->_messages['notice'][] = $message . ($appendImportButton ? $this->getImportButtonHtml() : '');
        }
        return $this;
    }

    /**
     * Add success message.
     * @param string[]|string $message Message text
     * @param bool $appendImportButton OPTIONAL Append import button to message?
     * @return $this
     */
    public function addSuccess($message, $appendImportButton = false)
    {
        if (is_array($message)) {
            foreach ($message as $row) {
                $this->addSuccess($row);
            }
        } else {
            $this->_messages['success'][] = $message . ($appendImportButton ? $this->getImportButtonHtml() : '');
        }
        return $this;
    }

    public function addUploadedFile($csv_file = '')
    {
        $this->uploaded_file = $csv_file;
    }

    /**
     * Import button HTML for append to message.
     * @return string
     */
    public function getImportButtonHtml()
    {
        return '&nbsp;&nbsp;<button onclick="varienImport.startImport(\'' .
            $this->getImportStartUrl() .
            '\', \'' .
            \Magento\ImportExport\Model\Import::FIELD_NAME_SOURCE_FILE .
            '\',\'' .$this->uploaded_file.'\');" class="scalable save"' .
            ' type="button"><span><span><span>' .
            __(
                'Import'
            ) . '</span></span></span></button>';
    }

    /**
     * Import start action URL.
     * @return string
     */
    public function getImportStartUrl()
    {
        return $this->getUrl('ict_shopbybrand/*/start');
    }

    /**
     * Messages getter.
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    /**
     * Messages rendered HTML getter.
     * @return string
     */
    public function getMessagesHtml()
    {
        /** @var $messagesBlock \Magento\Framework\View\Element\Messages */
        $messagesBlock = $this->_layout->createBlock('Magento\Framework\View\Element\Messages');

        foreach ($this->_messages as $priority => $messages) {
            $method = "add{$priority}";

            foreach ($messages as $message) {
                $messagesBlock->{$method}($message);
            }
        }
        return $messagesBlock->toHtml();
    }

    /**
     * Return response as JSON.
     * @return string
     */
    public function getResponseJson()
    {
        // add messages HTML if it is not already specified
        if (!isset($this->_actions['import_validation_messages'])) {
            $this->addAction('innerHTML', 'import_validation_messages', $this->getMessagesHtml());
        }
        return $this->_jsonEncoder->encode($this->_actions);
    }

    public function checkCSV($fields)
    {
        return array_diff($this->fields, $fields);
    }

    public function checkCSVRow($fields)
    {
        $empty_field = [];
        foreach ($this->fields_require as $key => $value) {
            if (empty(trim($fields[$value["position"]]))) {
                $empty_field[] = $value["key"];
            }
        }
        return $empty_field;
    }

    public function checkCSVDataRow($fields)
    {
        $empty_field = [];
        foreach ($this->fields_require as $key => $value) {
            if (isset($value["regular_expression"]) && !preg_match($value["regular_expression"], $fields[$value["position"]])) {
                $empty_field[$value["key"]] = $value["message"];
            }
        }
        return $empty_field;
    }
}
