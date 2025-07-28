<?php

namespace Core\Database;

trait MakeResult
{

    public function getResult($result)
    {
        return self::skipHidden($result);
    }
    /**
     * @param $result
     * @return array|mixed
     */
    private function skipHidden($result): mixed
    {
        try {
            if (is_array($result)) {
                return array_map([$this, 'skipHidden'], $result);
            }

            if (is_object($result)) {
                foreach ($this->hidden as $field) {
                    unset($result->$field);
                }
                return $result;
            }

            return $result;
        } catch (\Exception $e) {
            return $result;
        }
    }

}