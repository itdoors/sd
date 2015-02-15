<?php
/**
 * Created by PhpStorm.
 * User: silence4r4
 * Date: 24.10.14
 * Time: 20:21
 */

namespace ITDoors\HelperBundle\Classes;

/**
 * Class HiddenFields
 */
class HiddenFields
{

    private $hiddenFields;

    /**
     * @param string  $function
     * @param mixed[] $args
     *
     * @return $this|null
     */
    public function __call($function, $args)
    {
        $check = substr($function, 0, 3);
        $key = substr($function, 3);
        if ($check == 'get') {
            $value = $this->getHiddenField($key);

            return $value;
        } elseif ($check == 'set') {
            $this->setHiddenField($key, $args);

            return $this;
        }

    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getHiddenField($key)
    {
        if (isset($this->hiddenFields[$key])) {

            return $this->hiddenFields[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setHiddenField($key, $value)
    {
        $this->hiddenFields[$key] = $value;

        return $this;
    }
}
