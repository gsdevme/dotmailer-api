<?php

namespace Dotmailer\Entity;

/**
 * A contact record.
 * http://api.dotmailer.com/v2/help/wadl#ApiContact
 *
 * @extends Dotmailer\Entity
 */
class Contact extends Entity
{
    protected $id;
    public $email;
    public $optInType;
    public $emailType;
    protected $dataFields; // @TODO - Map to datafield entities?
    public $status;

    /* By array key or datafield name */
    public function getDatafield($key)
    {
        if (isset($this->dataFields[$key])) {
            return !empty($this->dataFields[$key]->value) ? $this->dataFields[$key]->value : '';
        }
        if (count($this->dataFields)) {
            foreach ($this->dataFields as $field) {
                if ($field->key == $key) {
                    return !empty($field->value) ? $field->value : '';
                }
            }
        }
        return '';
    }

    public function setDatafield($key, $value)
    {
        if (is_int($key) && isset($this->dataFields[$key])) {
            $this->dataFields[$key] = $value;
            return;
        }
        if (count($this->dataFields)) {
            foreach ($this->dataFields as $fieldkey => $field) {
                if ($field->key == $key) {
                    $this->dataFields[$fieldkey]->value = $value;
                    return;
                }
            }
        }
        // Add a new data field with this value.
        $field = new DataItem(
            array(
                'key' => $key,
                'value' => $value,
            )
        );
        $this->dataFields[] = $field;
    }
}
