<?php

namespace Crayon\NovaLanguageEditor\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController
{
    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $this->editConfigurationFile(base_path() . '/config/laravellocalization.php', 'supportedLocales', $data, 'localization');
        $this->editConfigurationFile(base_path() . '/config/nova-translatable.php', 'locales', $data, 'translatable');
        $this->editConfigurationFile(base_path() . '/config/nova-translation-editor.php', 'languages', $data, 'editor');
    }

    /**
     * Edits a config file that returns an array
     *
     * @param string $path
     * @param string $key
     * @param array $value
     * @param string $callback
     * @return bool
     */
    private function editConfigurationFile(string $path, string $key, array $value, $callback = ""): bool
    {
        $configuration = include($path);

        switch($callback) {
            case "localization":
                foreach(\App\Models\Region::whereIsRoot()->get() as $region) {
                    if($region->segment)
                        foreach($value as $locale => $val) {
                            if(strlen($locale) == 2)
                                $value[$locale . '_' . $region->segment] = $val;
                        }
                }
                $configuration[$key] = $value;
                break;
            case "editor":
                $configuration[$key] = array_keys($value);
                break;
            case "translatable":
                $configuration[$key] = $this->array_column_ext($value, "name", -1);
        }

        return file_put_contents($path, "<?php return " . preg_replace("/[0-9]+ \=\>/i", '', $this->formatArray($configuration, true)). ";");
    }

    /**
     * Adjust var_export to return short-array
     *
     * @param array $array
     * @param bool $return
     * @return string|string[]|null
     */
    private function formatArray(array $array, bool $return = false)
    {
        $export = var_export($array, TRUE);
        $patterns = [
            "/array \(/" => '[',
            "/^([ ]*)\)(,?)$/m" => '$1]$2',
            "/=>[ ]?\n[ ]+\[/" => '=> [',
            "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
        ];
        $export = preg_replace(array_keys($patterns), array_values($patterns), $export);
        if ((bool)$return) return $export; else echo $export;
    }

    /**
     * @param $array
     * @param $key
     * @param null $index
     * @return array
     */
    function array_column_ext($array, $key, $index = null): array
    {
        $result = array();
        foreach ($array as $subarray => $value) {
            if (array_key_exists($key,$value)) { $val = $array[$subarray][$key]; }
            else if ($key === null) { $val = $value; }
            else { continue; }

            if ($index === null) { $result[] = $val; }
            elseif ($index == -1 || array_key_exists($index,$value)) {
                $result[($index == -1)?$subarray:$array[$subarray][$index]] = $val;
            }
        }
        return $result;
    }
}
