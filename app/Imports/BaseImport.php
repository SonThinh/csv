<?php

namespace App\Imports;

class BaseImport
{
    /**
     * @param array $array
     * @param $keyRow
     * @return array
     */
    function changeKeySpliceId(array $array, $keyRow)
    {
        $keys = array_keys($array);
        $keys[array_search($keys, $keys)] = $this->headings();
        $combineKeyValue = array_combine($this->headings(), $array[$keyRow]);

        return $combineKeyValue;
    }

    /**
     * @param $path
     * @return array|false
     */
    public function importFile($path)
    {
        if (($handle = fopen($path, 'r')) !== false) {
            $countRow = 1;
            $flag = true;
            $errors = [];
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($flag) {
                    $flag = false;
                    $countRow++;
                    continue;
                }
                $countCol = count($data);
                for ($i = 0; $i < $countCol; $i++) {
                    $dataCsv[$countRow][$i] = $data[$i];
                }

                $arrChangeKey = $this->changeKeySpliceId($dataCsv, $countRow);
                $splice = array_splice($arrChangeKey, 1);
                $arrayData = $this->checkValidate($splice, $countRow);
                $countRow++;

                if (count($arrayData['data']) != 0) {
                    $this->addDb($arrayData['data'][0]);
                } else {
                    array_push($errors, $arrayData['error'][0]);
                }
            }
            fclose($handle);

            return $errors;
        }

        return false;
    }
}
