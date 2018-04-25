<?php

namespace Application\InputFilter;

use Zend\InputFilter\CollectionInputFilter;

/**
 * Class InformativeCollectionInputFilter
 *
 * @package Application\InputFilter
 */
class InformativeCollectionInputFilter extends CollectionInputFilter
{
    const COLLECTION_IS_EMPTY  = 'collectionIsEmpty';
    const COLLECTION_IS_SCALAR = 'collectionIsScalar';
    const COLLECTION_TOO_SHORT = 'collectionTooShort';
    const COLLECTION_TOO_LONG  = 'collectionTooLong';

    /**
     * @var int
     */
    protected $maximum = null;

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::COLLECTION_IS_EMPTY  => 'Collection must contain at least one item',
        self::COLLECTION_IS_SCALAR => 'Invalid type given. Array or object expected',
        self::COLLECTION_TOO_SHORT => 'The input is less than %d items',
        self::COLLECTION_TOO_LONG  => 'The input is more than %d items',
    ];

    /**
     * @return int
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * @param int $maximum
     *
     * @return $this
     */
    public function setMaximum($maximum)
    {
        $this->maximum = $maximum < 1 ? 1 : $maximum;
        return $this;
    }

    /**
     * Sets list of message templates
     *
     * @param array $messages
     *
     * @return self
     */
    public function setMessages(array $messages)
    {
        foreach ($messages as $key => $message) {
            $this->setMessage($key, $message);
        }
        return $this;
    }

    /**
     * Sets message template
     *
     * @param string $key
     * @param string $message
     *
     * @return self
     */
    public function setMessage($key, $message)
    {
        if (in_array($key, array_keys($this->messageTemplates), true)) {
            $this->messageTemplates[$key] = $message;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($context = null)
    {
        $valid = true;
        if ($this->getCount() < 1) {
            if ($this->isRequired) {
                $valid = false;
                $this->addMessage(self::COLLECTION_IS_EMPTY, $this->messageTemplates[self::COLLECTION_IS_EMPTY]);
            }
        }

        if (is_scalar($this->data)) {
            $valid = false;
            $this->addMessage(self::COLLECTION_IS_SCALAR, $this->messageTemplates[self::COLLECTION_IS_SCALAR]);
        }

        if (count($this->data) < $this->getCount()) {
            $valid = false;
            $this->addMessage(
                self::COLLECTION_TOO_SHORT,
                sprintf(
                    $this->messageTemplates[self::COLLECTION_TOO_SHORT],
                    $this->getCount()
                )
            );
        }

        if ($this->getMaximum() !== null && count($this->data) > $this->getMaximum()) {
            $valid = false;
            $this->addMessage(
                self::COLLECTION_TOO_LONG,
                sprintf(
                    $this->messageTemplates[self::COLLECTION_TOO_LONG],
                    $this->getMaximum()
                )
            );
        }

        return $valid === false ? false : parent::isValid();
    }

    /**
     * Adds message to list of messages
     *
     * @param string       $key
     * @param string|array $message
     */
    protected function addMessage($key, $message)
    {
        $this->collectionMessages[$key] = $message;
    }
}
