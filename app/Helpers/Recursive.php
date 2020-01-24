<?php

namespace App\Helpers;

class Recursive
{
    public static function make($data, $parent_name = null, $sub_name = null, $parent = 0)
    {
        if ($data->isEmpty()) {
            return;
        }

        $d = [];
        foreach ($data as $key => $value) {
            $id = $value->{$parent_name};
            $parent_id = is_null($value->{$sub_name}) ? 0 : $value->{$sub_name};
            if ($parent == $parent_id) {
                $value->{$sub_name} = self::make($data, $parent_name, $sub_name, $id);
                $data->forget($key);
                $d[$key] = $value;
            }
        }

        return $d;
    }

    public static function data($data = [], $name = null, $divider = '&nbsp;&nbsp;&nbsp;&nbsp;', $temp = [], $depth = 0)
    {
        $separator = null;
        for ($i = 0; $i < $depth; $i++) {
            $separator .= $divider;
        }

        if (empty($data) === false) {
            foreach ($data as $value) {
                $d = $value;
                if (is_null($name) === false) {
                    $d->{$name} = $separator . $d->{$name};
                }

                $d->_divider = $separator;
                $d->_depth = $depth;
                $temp[] = $d;

                if (empty($value->sub) === false) {
                    $new_depth = $depth + 1;
                    $temp = self::data($value->sub, $name, $divider, $temp, $new_depth);
                }
            }
        }

        return $temp;
    }
}
